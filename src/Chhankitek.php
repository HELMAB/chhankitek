<?php

namespace Asorasoft\Chhankitek;

use Exception;
use Illuminate\Support\Carbon;

/**
 * Class Chhankitek
 * @package Asorasoft\Chhankitek
 */
class Chhankitek
{
    public $lunarMonths;
    public $solarMonths;
    public $moonStatuses;
    public $animalYears;
    public $eraYears;

    public function __construct()
    {
        $config = new Constant();
        $this->lunarMonths = $config->lunarMonths;
        $this->solarMonths = $config->solarMonths;
        $this->moonStatuses = $config->moonStatuses;
        $this->animalYears = $config->animalYears;
        $this->eraYears = $config->eraYears;
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
     * Buddhist Era
     * ថ្ងៃឆ្លងឆ្នាំ គឺ ១ រោច ខែពិសាខ
     * http://news.sabay.com.kh/article/1039620
     * @param Carbon $carbon
     * @return int
     * @throws Exception
     */
    public function getBEYear(Carbon $carbon)
    {
        if ($carbon->diff($this->getVisakhaBochea((int)$carbon->format('Y'))) > 0) {
            return (int)$carbon->format('Y') + 544;
        } else {
            return (int)$carbon->format('Y') + 543;
        }
    }

    /**
     * រកថ្ងៃវិសាខបូជា
     * ថ្ងៃដាច់ឆ្នាំពុទ្ធសករាជ
     * @param $gregorianYear
     * @return \Carbon\Carbon|false
     * @throws Exception
     */
    public function getVisakhaBochea($gregorianYear)
    {
        $date = Carbon::createFromFormat('d/m/Y', "01/01/$gregorianYear");

        for ($i = 0; $i < 365; $i++) {
            $lunarDate = $this->findLunarDate($date);
            if ($lunarDate['month'] == $this->lunarMonths['ពិសាខ'] && $lunarDate['day'] == 14) {
                return $date;
            }
            $date->addDay();
        }

        throw new Exception('Cannot find Visakhabochea day. Please report this bug.');
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
        $gregorianYear = (int)$date->format('Y');
        $newYearMoment = $this->getKhNewYearMoment($gregorianYear);
        if ($date->diff($newYearMoment) < 0) {
            return $gregorianYear + 543 - 1182;
        } else {
            return $gregorianYear + 544 - 1182;;
        }
    }

    /**
     * ១កើត ៤កើត ២រោច ១៤រោច ...
     * @param $day
     * @return array
     */
    public function getKhmerLunarDay($day)
    {
        return [
            'count' => ($day % 15) + 1,
            'moonStatus' => $day > 14 ? $this->moonStatuses['រោច'] : $this->moonStatuses['កើត']
        ];
    }

    /**
     * Turn be year to animal year
     * @param Carbon $date
     * @return int
     */
    public function getAnimalYear(Carbon $date)
    {
        $gregorianYear = (int)$date->format('Y');
        $newYearMoment = $this->getKhNewYearMoment($gregorianYear);
        if ($date->diff($newYearMoment) < 0) {
            return ($gregorianYear + 543 + 4) % 12;
        } else {
            return ($gregorianYear + 544 + 4) % 12;
        }
    }

    public function formatKhmerDate(int $day, int $month, Carbon $date, string $format = null)
    {
        if (is_null($format)) {
            $dayOfWeek = $date->dayOfWeek;
            $moonDay = $this->getKhmerLunarDay($day);
            $beYear = $this->getBEYear($date);
            $animalYear = $this->getAnimalYear($date);
            $eraYear = $this->getJolakSakarajYear($date) % 10;

            return [
                $dayOfWeek,
                $moonDay['count'],
                $this->moonStatuses[$moonDay['moonStatus']],
                $this->lunarMonths[$month],
                $this->animalYears[$animalYear],
                $this->eraYears[$eraYear],
                $beYear
            ];
        }
    }
}
