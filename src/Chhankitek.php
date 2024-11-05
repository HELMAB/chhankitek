<?php

namespace Asorasoft\Chhankitek;

use Asorasoft\Chhankitek\Exception\InvalidKhmerMonthException;
use Asorasoft\Chhankitek\Exception\TimeOfNewYearException;
use Asorasoft\Chhankitek\Exception\VisakhabocheaException;
use Asorasoft\Chhankitek\Traits\HasKhmerNumberConversion;
use Carbon\Carbon;
use Exception;

/**
 * Class Chhankitek
 * @package Asorasoft\Chhankitek
 */
class Chhankitek
{
    use HasKhmerNumberConversion;

    public $target;
    public $formatKhmerDate;
    public $khNewYearDateTime;

    /**
     * Chhankitek constructor.
     * @param Carbon $target
     * @throws InvalidKhmerMonthException
     * @throws TimeOfNewYearException
     */
    public function __construct(Carbon $target)
    {
        $this->target = Carbon::createFromFormat('d/m/Y', $target->format('d/m/Y'));
        $this->target->setTimezone('Asia/Phnom_Penh');

        $this->formatKhmerDate = $this->khmerLunarDate($this->target);
        $this->khNewYearDateTime = $this->getKhmerNewYearDateTime($this->target->year);
    }

    /**
     * Bodithey: បូតិថី
     * Bodithey determines if a given beYear is a leap-month year.
     * Given year target year in Buddhist Era. Return (0-29)
     * @param int $beYear
     * @return int
     */
    public function getBodithey(int $beYear)
    {
        $ahk = $this->getAharkun($beYear);
        $avml = (int)(floor((11 * $ahk + 25) / 692));
        $m = $avml + $ahk + 29;
        return ($m % 30);
    }

    /**
     * Avoman: អាវមាន
     * Avoman determines if a given year is a leap-day year.
     * Given a year in Buddhist Era as denoted as adYear. Return (0 - 691)
     * @param int $beYear
     * @return int
     */
    public function getAvoman(int $beYear)
    {
        $ahk = $this->getAharkun($beYear);
        $avm = (11 * $ahk + 25) % 692;
        return $avm;
    }

    /**
     * Aharkun: អាហារគុណ ឬ ហារគុណ
     * Aharkun is used for Avoman and Bodithey calculation below.
     * Given adYear as a target year in Buddhist Era
     * @param int $beYear
     * @return int
     */
    public function getAharkun(int $beYear)
    {
        $t = ($beYear * 292207) + 499;
        return (int)floor($t / 800) + 4;
    }

    /**
     * Kromathupul
     * @param int $beYear
     * @return int
     */
    public function kromthupul(int $beYear)
    {
        $ah = $this->getAharkunMod($beYear);
        $krom = 800 - $ah;
        return $krom;
    }

    /**
     * @param int $beYear
     * @return int
     */
    public function isKhmerSolarLeap(int $beYear)
    {
        $krom = $this->kromthupul($beYear);
        if ($krom <= 207) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @param int $beYear
     * @return int
     */
    public function getAharkunMod(int $beYear)
    {
        $t = ($beYear * 292207) + 499;
        return $t % 800;
    }

    /**
     * Regular if year has 30 day
     * leap month if year has 13 months
     * leap day if Jesth month of the year has 1 extra day
     * leap day and month: both of them
     * return 0:regular, 1:leap month, 2:leap day, 3:leap day and month
     * @param int $beYear
     * @return int
     */
    public function getBoditheyLeap(int $beYear)
    {
        $avoman = $this->getAvoman($beYear);
        $bodithey = $this->getBodithey($beYear);

        // check bodithey leap month
        $boditheyLeap = 0;
        if ($bodithey >= 25 || $bodithey <= 5) {
            $boditheyLeap = 1;
        }

        // check for avoman leap-day based on gregorian leap
        $avomanLeap = 0;
        if ($this->isKhmerSolarLeap($beYear) == 1) {
            if ($avoman <= 126)
                $avomanLeap = 1;
        } else {
            if ($avoman <= 137) {
                // check for avoman case 137/0, 137 must be normal year (p.26)
                if ($this->getAvoman($beYear + 1) == 0) {
                    $avomanLeap = 0;
                } else {
                    $avomanLeap = 1;
                }
            }
        }

        // case of 25/5 consecutively
        // only bodithey 5 can be leap-month, so set bodithey 25 to none
        if ($bodithey == 25) {
            $nextBodithey = $this->getBodithey($beYear + 1);
            if ($nextBodithey == 5) {
                $boditheyLeap = 0;
            }
        }

        // case of 24/6 consecutively, 24 must be leap-month
        if ($bodithey == 24) {
            $nextBodithey = $this->getBodithey($beYear + 1);
            if ($nextBodithey == 6) {
                $boditheyLeap = 1;
            }
        }

        // format leap result (0:regular, 1:month, 2:day, 3:both)
        if ($boditheyLeap == 1 && $avomanLeap == 1) {
            $result = 3;
        } else if ($boditheyLeap == 1) {
            $result = 1;
        } else if ($avomanLeap == 1) {
            $result = 2;
        } else {
            $result = 0;
        }

        return $result;
    }

    /**
     * bodithey leap can be both leap-day and leap-month but following the khmer calendar rule
     * they can't be together on the same year, so leap day must be delayed to next year
     * return 0:regular, 1:leap month, 2:leap day (no leap month and day together)
     * @param $beYear
     * @return int
     */
    public function getProtetinLeap($beYear)
    {
        $b = $this->getBoditheyLeap($beYear);
        if ($b == 3) {
            return 1;
        }
        if ($b == 2 || $b == 1) {
            return $b;
        }
        // case of previous year is 3
        if ($this->getBoditheyLeap($beYear - 1) == 3) {
            return 2;
        }
        // normal case
        return 0;
    }

    /**
     * Maximum number of day in Khmer Month
     * @param $beMonth
     * @param $beYear
     * @return int
     */
    public function getNumberOfDayInKhmerMonth($beMonth, $beYear)
    {
        $lunarMonths = (new Constant())->lunarMonths;

        if ($beMonth == $lunarMonths['ជេស្ឋ'] && $this->isKhmerLeapDay($beYear)) {
            return 30;
        }

        if ($beMonth == $lunarMonths['បឋមាសាឍ'] || $beMonth == $lunarMonths['ទុតិយាសាឍ']) {
            return 30;
        }

        return $beMonth % 2 == 0 ? 29 : 30; // មិគសិរ : 29 , បុស្ស : 30 , មាឃ : 29 .. 30 .. 29 ..30 .....
    }

    /**
     * Get number of day in Khmer year
     * @param int $beYear
     * @return int
     */
    public function getNumberOfDayInKhmerYear(int $beYear)
    {
        if ($this->isKhmerLeapMonth($beYear)) {
            return 384;
        } else if ($this->isKhmerLeapDay($beYear)) {
            return 355;
        } else {
            return 354;
        }
    }

    /**
     * Get number of day in Gregorian year
     * @param $adYear
     * @return int
     */
    public function getNumberOfDayInGregorianYear($adYear)
    {
        if ($this->isGregorianLeap($adYear)) {
            return 366;
        } else {
            return 365;
        }
    }

    /**
     * A year with an extra month is called Adhikameas (អធិកមាស).
     * This year has 384 days.
     * @param int $beYear
     * @return bool
     */
    public function isKhmerLeapMonth(int $beYear)
    {
        return $this->getProtetinLeap($beYear) == 1;
    }

    /**
     * A year with an extra day is called Chhantrea Thimeas (ចន្ទ្រាធិមាស) or Adhikavereak (អធិកវារៈ).
     * This year has 355 days.
     * @param int $beYear
     * @return bool
     */
    public function isKhmerLeapDay(int $beYear)
    {
        return $this->getProtetinLeap($beYear) == 2;
    }

    /**
     * Gregorian Leap
     * @param int $adYear
     * @return bool
     */
    public function isGregorianLeap(int $adYear)
    {
        return $adYear % 4 == 0 && $adYear % 100 != 0 || $adYear % 400 == 0;
    }

    /**
     * រកថ្ងៃវិសាខបូជា
     * ថ្ងៃដាច់ឆ្នាំពុទ្ធសករាជ
     * @param int $gregorianYear
     * @return Carbon|VisakhabocheaException
     * @throws InvalidKhmerMonthException
     * @throws VisakhabocheaException
     */
    public function getVisakhaBochea(int $gregorianYear)
    {
        $lunarMonths = (new Constant())->lunarMonths;
        $date = Carbon::createFromFormat('d/m/Y', "1/1/$gregorianYear");

        for ($i = 0; $i < 365; $i++) {
            $lunarDate = $this->findLunarDate($date);
            if ($lunarDate->getMonth() == $lunarMonths['ពិសាខ'] && $lunarDate->getDay() == 14) {
                return $date;
            }
            $date->addDay();
        }

        throw new VisakhabocheaException('Cannot find Visakhabochea day.');
    }

    /**
     * Buddhist Era
     * ថ្ងៃឆ្លងឆ្នាំ គឺ ១ រោច ខែពិសាខ
     * http://news.sabay.com.kh/article/1039620
     * @return int
     * @throws Exception
     */
    public function getBEYear(Carbon $target)
    {
        if (($this->getVisakhaBochea($target->year))->diffInMilliseconds($target, false) > 0) {
            return $target->year + 544;
        } else {
            return $target->year + 543;
        }
    }

    /**
     * Due to recursive problem, I need to calculate the BE based on new year's day
     * This won't be displayed on final result, it is used to find number of day in year,
     * It won't affect the result because on ខែចេត្រ និង ខែពិសាខ, number of days is the same every year
     * ពីព្រោះចូលឆ្នាំតែងតែចំខែចេត្រ ឬ ពិសាខ
     * @param Carbon $date
     * @return int
     */
    public function getMaybeBEYear(Carbon $date)
    {
        $constant = new Constant();

        if ($date->month <= $constant->solarMonths['មេសា'] + 1) {
            return $date->year + 543;
        } else {
            return $date->year + 544;
        }
    }

    /**
     * Moha Sakaraj
     * @param $adYear
     * @return int
     */
    public function getMohaSakarajYear($adYear)
    {
        return $adYear - 77;
    }

    /**
     * Jolak Sakaraj
     * @param Carbon $date
     * @return int
     */
    public function getJolakSakarajYear(Carbon $date)
    {
        $gregorianYear = $date->year;
        $newYearMoment = $this->getKhmerNewYearDateTime($gregorianYear);
        if ($newYearMoment->diffInMilliseconds($date, false) < 0) {
            return $gregorianYear + 543 - 1182;
        } else {
            return $gregorianYear + 544 - 1182;
        }
    }

    /**
     * ១កើត ៤កើត ២រោច ១៤រោច ...
     * @param $day
     * @return LunarDay
     */
    public function getKhmerLunarDay($day)
    {
        $moonStatuses = (new Constant())->moonStatuses;

        return new LunarDay(
            ($day % 15) + 1,
            $day > 14 ? $moonStatuses['រោច'] : $moonStatuses['កើត']
        );
    }

    /**
     * Turn be year to animal year
     * @param Carbon $date
     * @return int
     */
    public function getAnimalYear(Carbon $date)
    {
        $gregorianYear = $date->year;
        $newYearDateTime = $this->getKhmerNewYearDateTime($gregorianYear);
        if ($newYearDateTime->diffInMilliseconds($date, false) < 0) {
            return ($gregorianYear + 543 + 4) % 12;
        } else {
            return ($gregorianYear + 544 + 4) % 12;
        }
    }

    /**
     * @param Carbon $target
     * @return KhmerLunarDate
     * @throws InvalidKhmerMonthException
     */
    public function khmerLunarDate(Carbon $target)
    {
        $constant = new Constant();

        $current = $target->copy();
        $lunar = $this->findLunarDate($current);

        $dayOfWeek = $target->dayOfWeek;
        $moonDay = $this->getKhmerLunarDay($lunar->getDay());
        $beYear = $this->getBEYear($target);
        $animalYear = $this->getAnimalYear($target);
        $eraYear = $this->getJolakSakarajYear($target) % 10;

        $lunarMonth = array_search($moonDay->getMoonStatus(), $constant->moonStatuses);
        $lunarDays = $this->convertToKhmerNumber($moonDay->getMoonCount());
        $lunarDay = "$lunarDays $lunarMonth";

        return new KhmerLunarDate(
            array_search($dayOfWeek, $constant->dayOfWeeks),
            $lunarDay,
            array_search($lunar->getMonth(), $constant->lunarMonths),
            array_search($animalYear, $constant->animalYears),
            array_search($eraYear, $constant->eraYears),
            $this->convertToKhmerNumber($beYear)
        );
    }

    /**
     * Next month of the month
     */
    public function nextMonthOf($khmerMonth, $BEYear)
    {
        $lunarMonths = (new Constant())->lunarMonths;

        switch ($khmerMonth) {
            case $lunarMonths['មិគសិរ']:
                return $lunarMonths['បុស្ស'];
            case $lunarMonths['បុស្ស']:
                return $lunarMonths['មាឃ'];
            case $lunarMonths['មាឃ']:
                return $lunarMonths['ផល្គុន'];
            case $lunarMonths['ផល្គុន']:
                return $lunarMonths['ចេត្រ'];
            case $lunarMonths['ចេត្រ']:
                return $lunarMonths['ពិសាខ'];
            case $lunarMonths['ពិសាខ']:
                return $lunarMonths['ជេស្ឋ'];
            case $lunarMonths['ជេស្ឋ']:
                if ($this->isKhmerLeapMonth($BEYear)) {
                    return $lunarMonths['បឋមាសាឍ'];
                } else {
                    return $lunarMonths['អាសាឍ'];
                }
            case $lunarMonths['ទុតិយាសាឍ']:
            case $lunarMonths['អាសាឍ']:
                return $lunarMonths['ស្រាពណ៍'];
            case $lunarMonths['ស្រាពណ៍']:
                return $lunarMonths['ភទ្របទ'];
            case $lunarMonths['ភទ្របទ']:
                return $lunarMonths['អស្សុជ'];
            case $lunarMonths['អស្សុជ']:
                return $lunarMonths['កត្តិក'];
            case $lunarMonths['កត្តិក']:
                return $lunarMonths['មិគសិរ'];
            case $lunarMonths['បឋមាសាឍ']:
                return $lunarMonths['ទុតិយាសាឍ'];
            default:
                throw new InvalidKhmerMonthException('Invalid khmer month');
        }
    }

    /**
     * Calculate date to Khmer date
     * @param Carbon $target
     * @return LunarDate
     * @throws InvalidKhmerMonthException
     */
    public function findLunarDate(Carbon $target)
    {
        $lunarMonths = (new Constant())->lunarMonths;
        // Epoch Date: January 1, 1900
        $epochDateTime = Carbon::createFromFormat('d/m/Y', '1/1/1900');
        $khmerMonth = $lunarMonths['បុស្ស'];
        $khmerDay = 0; // 0 - 29 ១កើត ... ១៥កើត ១រោច ...១៤រោច (១៥រោច)

        // Find nearest year epoch
        $differentFromEpoch = $target->diffInMilliseconds($epochDateTime);
        if ($differentFromEpoch > 0) {
            while ($target->diffInDays($epochDateTime) > $this->getNumberOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime->copy()->addYear()))) {
                $epochDateTime->addDays($this->getNumberOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime->copy()->addYear())));
            }
        } else {
            do {
                $epochDateTime->subDays($this->getNumberOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime)));
            } while ($epochDateTime->diffInDays($target) > 0);
        }

        // Move epoch month
        while ($target->diffInDays($epochDateTime) > $this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($epochDateTime))) {
            $epochDateTime->addDays($this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($epochDateTime)));
            $khmerMonth = $this->nextMonthOf($khmerMonth, $this->getMaybeBEYear($epochDateTime));
        }

        $khmerDay += (int)floor($target->diffInDays($epochDateTime));

        /**
         * Fix result display 15 រោច ខែ ជេស្ឋ នៅថ្ងៃ ១ កើតខែបឋមាសាធ
         * ករណី ខែជេស្ឋមានតែ ២៩ ថ្ងៃ តែលទ្ធផលបង្ហាញ ១៥រោច ខែជេស្ឋ
         */
        $totalDaysOfTheMonth = $this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($target));
        if ($totalDaysOfTheMonth <= $khmerDay) {
            $khmerDay = $khmerDay % $totalDaysOfTheMonth;
            $khmerMonth = $this->nextMonthOf($khmerMonth, $this->getMaybeBEYear($epochDateTime));
        }
        $epochDateTime->addDays($target->diffInDays($epochDateTime));

        return new LunarDate($khmerDay, $khmerMonth, $epochDateTime);
    }

    /**
     * ថ្ងៃ ខែ ឆ្នាំ ម៉ោង និង នាទី ចូលឆ្នាំ
     * @param int $gregorianYear
     * @return Carbon|false
     * @throws InvalidKhmerMonthException
     * @throws TimeOfNewYearException
     */
    public function getKhmerNewYearDateTime(int $gregorianYear)
    {
        $jsYear = ($gregorianYear + 544) - 1182;
        $info = new SoriyatraLerngSak($jsYear);

        $newYearsDaySotins = $info->getNewYearDaySotins();
        if ($newYearsDaySotins[0]->getAngsar() == 0) {
            $numberNewYearDay = 4;
        } else {
            $numberNewYearDay = 3;
        }

        $timeOfNewYear = $info->getTimeOfNewYear();
        $minutes = sprintf("%02d", $timeOfNewYear->getMinute());
        $hour = $timeOfNewYear->getHour();

        $epochLerngSak = Carbon::createFromFormat('Y-m-d H:i', "$gregorianYear-04-17 $hour:$minutes");

        $lunarDate = $this->findLunarDate($epochLerngSak);
        $lunarDay = $lunarDate->getDay();
        $lunarMonth = $lunarDate->getMonth();

        $lunarLerngSak = $info->getLunarDateLerngSak();
        $lunarLerngSakDay = $lunarLerngSak->getDay();
        $lunarLerngSakMonth = $lunarLerngSak->getMonth();

        $diffFromEpoch = ((($lunarMonth - 4) * 30) + $lunarDay) - ((($lunarLerngSakMonth - 4) * 30) + $lunarLerngSakDay);

        return $epochLerngSak->subDays($diffFromEpoch + $numberNewYearDay - 1);
    }
}
