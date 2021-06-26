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

    public $lunarMonths;
    public $solarMonths;
    public $moonStatuses;
    public $animalYears;
    public $eraYears;
    public $khNewYearMoments;
    public $dayOfWeeks;
    public $khmerNumbers;

    public function __construct()
    {
        $config = new Constant();
        $this->lunarMonths = $config->lunarMonths;
        $this->solarMonths = $config->solarMonths;
        $this->moonStatuses = $config->moonStatuses;
        $this->animalYears = $config->animalYears;
        $this->eraYears = $config->eraYears;
        $this->khNewYearMoments = $config->khNewYearMoments;
        $this->dayOfWeeks = $config->dayOfWeeks;
        $this->khmerNumbers = $config->khmerNumbers;
    }

    /**
     * Get number of day in Khmer year
     * @param int $beYear
     * @return int
     */
    public function getNumerOfDayInKhmerYear(int $beYear)
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
     * A year with an extra month is called Adhikameas (អធិកមាស).
     * This year has 384 days.
     * @param int $beYear
     * @return bool
     */
    public function isKhmerLeapMonth(int $beYear)
    {
        return $this->getProtetinLeap($beYear) === 1;
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
        if ($b === 3) {
            return 1;
        }
        if ($b === 2 || $b === 1) {
            return $b;
        }
        // case of previous year is 3
        if ($this->getBoditheyLeap($beYear - 1) === 3) {
            return 2;
        }
        // normal case
        return 0;
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
        $result = 0;
        $avoman = $this->getAvoman($beYear);
        $bodithey = $this->getBodithey($beYear);

        // check bodithey leap month
        $boditheyLeap = 0;
        if ($bodithey >= 25 || $bodithey <= 5) {
            $boditheyLeap = 1;
        }
        // check for avoman leap-day based on gregorian leap
        $avomanLeap = 0;
        if ($this->isKhmerSolarLeap($beYear)) {
            if ($avoman <= 126)
                $avomanLeap = 1;
        } else {
            if ($avoman <= 137) {
                // check for avoman case 137/0, 137 must be normal year (p.26)
                if ($this->getAvoman($beYear + 1) === 0) {
                    $avomanLeap = 0;
                } else {
                    $avomanLeap = 1;
                }
            }
        }

        // case of 25/5 consecutively
        // only bodithey 5 can be leap-month, so set bodithey 25 to none
        if ($bodithey === 25) {
            $nextBodithey = $this->getBodithey($beYear + 1);
            if ($nextBodithey === 5) {
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
        if ($boditheyLeap === 1 && $avomanLeap === 1) {
            $result = 3;
        } else if ($boditheyLeap === 1) {
            $result = 1;
        } else if ($avomanLeap === 1) {
            $result = 2;
        } else {
            $result = 0;
        }

        return $result;
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
        $t = $beYear * 292207 + 499;
        return floor($t / 800) + 4;
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
        $avml = floor((11 * $ahk + 25) / 692);
        $m = $avml + $ahk + 29;
        return ($m % 30);
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
    public function getAharkunMod(int $beYear)
    {
        $t = $beYear * 292207 + 499;
        $ahkmod = $t % 800;
        return $ahkmod;
    }

    /**
     * A year with an extra day is called Chhantrea Thimeas (ចន្ទ្រាធិមាស) or Adhikavereak (អធិកវារៈ).
     * This year has 355 days.
     * @param int $beYear
     * @return bool
     */
    public function isKhmerLeapDay(int $beYear)
    {
        return $this->getProtetinLeap($beYear) === 2;
    }

    /**
     * Get number of day in Gregorian year
     * @param int $adYear
     * @return int
     */
    public function getNumberOfDayInGregorianYear(int $adYear)
    {
        if ($this->isGregorianLeap($adYear)) {
            return 366;
        } else {
            return 365;
        }
    }

    /**
     * Gregorian Leap
     * @param int $adYear
     * @return bool
     */
    public function isGregorianLeap(int $adYear)
    {
        if ($adYear % 4 === 0 && $adYear % 100 !== 0 || $adYear % 400 === 0) {
            return true;
        } else {
            return false;
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
     * @param Carbon $target
     * @return KhmerLunarDate
     * @throws InvalidKhmerMonthException
     */
    public function khmerLunarDate(Carbon $target)
    {
        $lunar = $this->findLunarDate($target);
        $khmerLunarDay = $this->getKhmerLunarDay($lunar->getDay());
        $beYear = $this->getBEYear($target);
        $lunarZodiac = $this->getAnimalYear($target);
        $lunarEra = $this->getJolakSakarajYear($target) % 10;

        $lunarMonth = array_search($khmerLunarDay->getMoonStatus(), $this->moonStatuses);
        $lunarDays = $this->convertToKhmerNumber($khmerLunarDay->getMoonCount());
        $lunarDay = "$lunarDays $lunarMonth";

        return new KhmerLunarDate(
            array_search($target->dayOfWeek, $this->dayOfWeeks),
            $lunarDay,
            array_search($lunar->getMonth(), $this->lunarMonths),
            array_search($lunarZodiac, $this->animalYears),
            array_search($lunarEra, $this->eraYears),
            $this->convertToKhmerNumber($beYear)
        );
    }

    /**
     * Calculate date to Khmer date
     * @param Carbon $target
     * @return LunarDate
     * @throws InvalidKhmerMonthException
     */
    public function findLunarDate(Carbon $target)
    {
        // Epoch Date: January 1, 1900
        $epochDateTime = Carbon::createFromFormat('d/m/Y', '01/01/1900');
        $khmerMonth = $this->lunarMonths['បុស្ស'];
        $khmerDay = 0; // 0 - 29 ១កើត ... ១៥កើត ១រោច ...១៤រោច (១៥រោច)

        // $differentFromEpoch = $target->diff($epochDateTime)->f; // milliseconds

        // Find nearest year epoch
        /*if ($differentFromEpoch > 0) {
            while ($target->diff($epochDateTime)->days > $this->getNumerOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime->copy()->addYear()))) {
                $epochDateTime->addDays($this->getNumerOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime->copy()->addYear())));
            }
        } else {
            do {
                $epochDateTime->subDays($this->getNumerOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime)));
                Log::info('$epochDateTime ' . $epochDateTime->format('d/m/Y'));
                Log::info('$target ' . $target->format('d/m/Y'));
            } while ($epochDateTime->diff($target)->days > 0);
        }*/

        // Move epoch month
        while ($target->diff($epochDateTime)->days > $this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($epochDateTime))) {
            $epochDateTime->addDays($this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($epochDateTime)));
            $khmerMonth = $this->nextMonthOf($khmerMonth, $this->getMaybeBEYear($epochDateTime));
        }

        $khmerDay += floor($target->diff($epochDateTime)->days);

        /**
         * Fix result display 15 រោច ខែ ជេស្ឋ នៅថ្ងៃ ១ កើតខែបឋមាសាធ
         * ករណី ខែជេស្ឋមានតែ ២៩ ថ្ងៃ តែលទ្ធផលបង្ហាញ ១៥រោច ខែជេស្ឋ
         */
        $totalDaysOfTheMonth = $this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($target));
        if ($totalDaysOfTheMonth <= $khmerDay) {
            $khmerDay = $khmerDay % $totalDaysOfTheMonth;
            $khmerMonth = $this->nextMonthOf($khmerMonth, $this->getMaybeBEYear($epochDateTime));
        }

        $epochDateTime->addDays($target->diff($epochDateTime)->days);

        $day = $khmerDay;
        $month = $khmerMonth;
        $epochMoved = $epochDateTime;

        return new LunarDate($day, $month, $epochMoved);
    }

    /**
     * Maximum number of day in Khmer Month
     * @param $beMonth
     * @param $beYear
     * @return int
     */
    public function getNumberOfDayInKhmerMonth($beMonth, $beYear)
    {
        if ($beMonth === $this->lunarMonths['ជេស្ឋ'] && $this->isKhmerLeapDay($beYear)) {
            return 30;
        }

        if ($beMonth === $this->lunarMonths['បឋមាសាឍ'] || $beMonth === $this->lunarMonths['ទុតិយាសាឍ']) {
            return 30;
        }

        // មិគសិរ : 29 , បុស្ស : 30 , មាឃ : 29 .. 30 .. 29 ..30 .....
        return $beMonth % 2 === 0 ? 29 : 30;
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
        if ((int)$date->format('m') <= $this->solarMonths['មេសា'] + 1) {
            return (int)$date->format('Y') + 543;
        } else {
            return (int)$date->format('Y') + 544;
        }
    }

    /**
     * Next month of the month
     */
    public function nextMonthOf($khmerMonth, $BEYear)
    {
        switch ($khmerMonth) {
            case $this->lunarMonths['មិគសិរ']:
                return $this->lunarMonths['បុស្ស'];
            case $this->lunarMonths['បុស្ស']:
                return $this->lunarMonths['មាឃ'];
            case $this->lunarMonths['មាឃ']:
                return $this->lunarMonths['ផល្គុន'];
            case $this->lunarMonths['ផល្គុន']:
                return $this->lunarMonths['ចេត្រ'];
            case $this->lunarMonths['ចេត្រ']:
                return $this->lunarMonths['ពិសាខ'];
            case $this->lunarMonths['ពិសាខ']:
                return $this->lunarMonths['ជេស្ឋ'];
            case $this->lunarMonths['ជេស្ឋ']:
                if ($this->isKhmerLeapMonth($BEYear)) {
                    return $this->lunarMonths['បឋមាសាឍ'];
                } else {
                    return $this->lunarMonths['អាសាឍ'];
                }
            case $this->lunarMonths['ទុតិយាសាឍ']:
            case $this->lunarMonths['អាសាឍ']:
                return $this->lunarMonths['ស្រាពណ៍'];
            case $this->lunarMonths['ស្រាពណ៍']:
                return $this->lunarMonths['ភទ្របទ'];
            case $this->lunarMonths['ភទ្របទ']:
                return $this->lunarMonths['អស្សុជ'];
            case $this->lunarMonths['អស្សុជ']:
                return $this->lunarMonths['កក្ដិក'];
            case $this->lunarMonths['កក្ដិក']:
                return $this->lunarMonths['មិគសិរ'];
            case $this->lunarMonths['បឋមាសាឍ']:
                return $this->lunarMonths['ទុតិយាសាឍ'];
            default:
                throw new InvalidKhmerMonthException('Invalid khmer month');
        }
    }

    /**
     * ១កើត ៤កើត ២រោច ១៤រោច ...
     * @param $day
     * @return LunarDay
     */
    public function getKhmerLunarDay($day)
    {
        $moonCount = ($day % 15) + 1;
        $moonStatus = $day > 14 ? $this->moonStatuses['រោច'] : $this->moonStatuses['កើត'];

        return new LunarDay($moonCount, $moonStatus);
    }

    /**
     * Buddhist Era
     * ថ្ងៃឆ្លងឆ្នាំ គឺ ១ រោច ខែពិសាខ
     * http://news.sabay.com.kh/article/1039620
     * @param Carbon $carbon
     * @return int
     * @throws Exception
     */
    public function getBEYear(Carbon $carbon)
    {
        if ($carbon->diff($this->getVisakhaBochea((int)$carbon->format('Y')))->f > 0) {
            return (int)$carbon->format('Y') + 544;
        } else {
            return (int)$carbon->format('Y') + 543;
        }
    }

    /**
     * រកថ្ងៃវិសាខបូជា
     * ថ្ងៃដាច់ឆ្នាំពុទ្ធសករាជ
     * @param $gregorianYear
     * @return Carbon|false
     * @throws Exception
     */
    public function getVisakhaBochea($gregorianYear)
    {
        $date = Carbon::createFromFormat('d/m/Y', "01/01/$gregorianYear");

        for ($i = 0; $i < 365; $i++) {
            $lunarDate = $this->findLunarDate($date);
            if ($lunarDate->getMonth() == $this->lunarMonths['ពិសាខ'] && $lunarDate->getDay() == 14) {
                return $date;
            }
            $date->addDay();
        }

        throw new VisakhabocheaException('Cannot find Visakhabochea day.');
    }

    /**
     * Turn be year to animal year
     * @param Carbon $date
     * @return int
     */
    public function getAnimalYear(Carbon $date)
    {
        $gregorianYear = (int)$date->format('Y');
        $newYearMoment = $this->getKhmerNewYearDateTime($gregorianYear);
        if ($date->diff($newYearMoment)->f < 0) {
            return ($gregorianYear + 543 + 4) % 12;
        } else {
            return ($gregorianYear + 544 + 4) % 12;
        }
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
        $info = new KhmerNewYearCalculation($jsYear);
        $numberNewYearDay = 0;

        $newYearsDaySotins = $info->getKhmerNewYear()->getNewYearsDaySotins();
        if (is_array($newYearsDaySotins) && count($newYearsDaySotins) > 0) {
            $newYearsDaySotin = $newYearsDaySotins[0];
            if ($newYearsDaySotin instanceof NewYearDaySotins) {
                if ($newYearsDaySotin->getAngsar() == 0) {
                    $numberNewYearDay = 4;
                } else {
                    $numberNewYearDay = 3;
                }
            }
        }

        $newYearTime = $info->getKhmerNewYear()->getTimeOfNewYear();

        $year = $gregorianYear;
        $month = 4;
        $day = 17;
        $hour = $newYearTime->getHour();
        $minute = $newYearTime->getMinute();

        $epochLerngSak = Carbon::createFromFormat('Y-m-d H:i', "$year-$month-$day $hour:$minute");

        $lunarDate = $this->findLunarDate($epochLerngSak);

        $diffFromEpoch = ((($lunarDate->getMonth() - 4) * 30) + $lunarDate->getDay()) -
            ((($info->getKhmerNewYear()->getLunarDateLerngSak()->getMonth() - 4) * 30)
                + $info->getKhmerNewYear()->getLunarDateLerngSak()->getDay());

        return $epochLerngSak->subMinutes($diffFromEpoch + $numberNewYearDay - 1);
    }

    /**
     * Jolak Sakaraj
     * @param Carbon $date
     * @return int
     */
    public function getJolakSakarajYear(Carbon $date)
    {
        $gregorianYear = (int)$date->format('Y');
        $newYearMoment = $this->getKhmerNewYearDateTime($gregorianYear);
        if ($date->diff($newYearMoment)->f < 0) {
            return $gregorianYear + 543 - 1182;
        } else {
            return $gregorianYear + 544 - 1182;
        }
    }
}
