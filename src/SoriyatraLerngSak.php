<?php

namespace Asorasoft\Chhankitek;

use Asorasoft\Chhankitek\Exception\TimeOfNewYearException;

class SoriyatraLerngSak
{
    public $jsYear;
    public $info;
    public $has366day;
    public $isAthikameas;
    public $isChantreathimeas;
    public $jesthHas30;
    public $dayLerngSak;
    public $lunarDateLerngSak;
    public $newYearsDaySotins;
    public $timeOfNewYear;
    public $khmerNewYear;

    /**
     * SoriyatraLerngSak constructor.
     * @param $jsYear
     * @throws TimeOfNewYearException
     */
    public function __construct($jsYear)
    {
        $this->jsYear = $jsYear;
        $this->info = $this->getInfo($jsYear);
        $this->has366day = $this->getHas366day($jsYear);
        $this->isAthikameas = $this->getIsAthikameas($jsYear);
        $this->isChantreathimeas = $this->getIsChantreathimeas($jsYear);
        $this->jesthHas30 = $this->jesthHas30();
        $this->dayLerngSak = ($this->info->getHarkun() - 2) % 7;
        $this->lunarDateLerngSak = $this->getLunarDateLerngSak();
        $this->newYearsDaySotins = $this->getNewYearDaySotins();
        $this->timeOfNewYear = $this->getTimeOfNewYear();
        $this->khmerNewYear = new KhmerNewYear(
            $this->info->getHarkun(),
            $this->info->getKromathopol(),
            $this->info->getAvaman(),
            $this->info->getBodithey(),
            $this->has366day,
            $this->isAthikameas,
            $this->isChantreathimeas,
            $this->jesthHas30,
            $this->dayLerngSak,
            $this->lunarDateLerngSak,
            $this->newYearsDaySotins,
            $this->timeOfNewYear
        );
    }

    /**
     * គណនា ហារគុន Kromathopol អវមាន និង បូតិថី
     * @param $jsYear
     * @return YearInfo
     */
    public function getInfo($jsYear)
    {
        $h = 292207 * $jsYear + 373;
        $harkun = (int)floor($h / 800) + 1;
        $kromathopol = 800 - ($h % 800);

        $a = 11 * $harkun + 650;
        $avaman = $a % 692;
        $bodithey = (int)($harkun + floor(($a / 692))) % 30;

        return new YearInfo($harkun, $kromathopol, $avaman, $bodithey);
    }

    /**
     * ឆ្នាំចុល្លសករាជដែលមាន៣៦៦ថ្ងៃ
     * @param $jsYear
     * @return bool
     */
    public function getHas366day($jsYear)
    {
        $infoOfYear = $this->getInfo($jsYear);
        return $infoOfYear->getKromathopol() <= 207;
    }

    /**
     * រកឆ្នាំអធិកមាស (មួយឆ្នាំមាន១៣ខែ)
     * @param $jsYear
     * @return bool
     */
    public function getIsAthikameas($jsYear)
    {
        $infoOfYear = $this->getInfo($jsYear);
        $infoOfNextYear = $this->getInfo($jsYear + 1);

        return (!($infoOfYear->getBodithey() == 25 && $infoOfNextYear->getBodithey() == 5) &&
            ($infoOfYear->getBodithey() > 24 ||
                $infoOfYear->getBodithey() < 6 ||
                ($infoOfYear->getBodithey() == 24 &&
                    $infoOfNextYear->getBodithey() == 6
                )
            )
        );
    }

    /**
     * រកឆ្នាំចន្ទ្រាធិមាស
     * @param $jsYear
     * @return bool
     */
    public function getIsChantreathimeas($jsYear)
    {
        $infoOfYear = $this->getInfo($jsYear);
        $infoOfNextYear = $this->getInfo($jsYear + 1);
        $infoOfPreviousYear = $this->getInfo($jsYear - 1);
        $has366day = $this->getHas366day($jsYear);

        return (($has366day && $infoOfYear->getAvaman() < 127) ||
            (!($infoOfYear->getAvaman() == 137 &&
                    $infoOfNextYear->getAvaman() == 0) &&
                ((!$has366day &&
                        $infoOfYear->getAvaman() < 138) ||
                    ($infoOfPreviousYear->getAvaman() == 137 &&
                        $infoOfYear->getAvaman() == 0
                    )
                )
            )
        );
    }

    /**
     * ឆែកមើលថាជាឆ្នាំដែលខែជេស្ឋមាន៣០ថ្ងៃឬទេ
     *
     * @param int $jsYear
     * @return bool
     */
    public function jesthHas30()
    {
        $tmp = $this->isChantreathimeas;

        if ($this->isAthikameas && $this->isChantreathimeas) {
            $tmp = false;
        }

        if (
            !$this->isChantreathimeas &&
            $this->getIsAthikameas($this->jsYear - 1) &&
            $this->getIsChantreathimeas($this->jsYear - 1)
        ) {
            $tmp = true;
        }

        return $tmp;
    }

    /**
     * គណនារកថ្ងៃឡើងស័ក
     * @return LunarDateLerngSak
     */
    public function getLunarDateLerngSak()
    {
        $lunarMonths = (new Constant())->lunarMonths;
        $bodithey = $this->info->getBodithey();

        if ($this->getIsAthikameas($this->jsYear - 1) && $this->getIsChantreathimeas($this->jsYear - 1)) {
            $bodithey = ($bodithey + 1) % 30;
        }

        return new LunarDateLerngSak(
            $bodithey >= 6 ? $bodithey - 1 : $bodithey,
            $bodithey >= 6 ? $lunarMonths['ចេត្រ'] : $lunarMonths['ពិសាខ']
        );
    }

    /**
     * @param int $sotin
     * @return SunInfo
     */
    public function getSunInfo(int $sotin)
    {
        $infoOfPreviousYear = $this->getInfo($this->jsYear - 1);

        // ១ រាសី = ៣០ អង្សា
        // ១ អង្សា = ៦០ លិប្ដា
        // មធ្យមព្រះអាទិត្យ គិតជាលិប្ដា
        $sunAverageAsLibda = $this->getSunAverageAsLibda($sotin, $infoOfPreviousYear);
        $leftOver = $this->getLeftOver($sunAverageAsLibda);
        $kaen = (int)floor($leftOver / (30 * 60));
        $lastLeftOver = $this->getLastLeftOver($kaen, $leftOver);

        // ខណ្ឌ និង pouichalip
        if ($lastLeftOver->getAngsar() >= 15) {
            $khan = 2 * $lastLeftOver->getReasey() + 1;
            $pouichalip = 60 * ($lastLeftOver->getAngsar() - 15) + $lastLeftOver->getLibda();
        } else {
            $khan = 2 * $lastLeftOver->getReasey();
            $pouichalip = 60 * $lastLeftOver->getAngsar() + $lastLeftOver->getLibda();
        }

        // phol
        $phol = $this->getPhol($khan, $pouichalip);
        $pholAsLibda = (30 * 60 * $phol->getReasey()) + (60 * $phol->getAngsar()) + $phol->getLibda();
        if ($kaen <= 5) {
            // សម្ពោធព្រះអាទិត្យ
            $sunInaugurationAsLibda = $sunAverageAsLibda - $pholAsLibda;
        } else {
            $sunInaugurationAsLibda = $sunAverageAsLibda + $pholAsLibda;
        }

        return new SunInfo($sunAverageAsLibda, $khan, $pouichalip, $phol, $sunInaugurationAsLibda);
    }

    /**
     * @param int $sotin
     * @param YearInfo $info
     * @return float|int
     */
    public function getSunAverageAsLibda(int $sotin, YearInfo $info)
    {
        $r2 = 800 * $sotin + $info->getKromathopol();
        $reasey = (int)floor($r2 / 24350); // រាសី
        $r3 = $r2 % 24350;
        $angsar = (int)floor($r3 / 811); // អង្សា
        $r4 = $r3 % 811;
        $l1 = (int)floor($r4 / 14);
        $libda = $l1 - 3; // លិប្ដា
        return (30 * 60 * $reasey) + (60 * $angsar) + $libda;
    }

    /**
     * @param $sunAverageAsLibda
     * @return float|int
     */
    public function getLeftOver($sunAverageAsLibda)
    {
        $s1 = ((30 * 60 * 2) + (60 * 20));
        $leftOver = $sunAverageAsLibda - $s1; // មធ្យមព្រះអាទិត្យ - R2.A20.L0
        if ($sunAverageAsLibda < $s1) { // បើតូចជាង ខ្ចី ១២ រាសី
            $leftOver += (30 * 60 * 12);
        }
        return $leftOver;
    }

    /**
     * @param int $kaen
     * @param int $leftOver
     * @return LastLeftOver
     */
    public function getLastLeftOver(int $kaen, int $leftOver)
    {
        $rs = -1;
        $l1 = [0, 1, 2];
        $l2 = [3, 4, 5];
        $l3 = [6, 7, 8];
        $l4 = [9, 10, 11];

        if (in_array($kaen, $l1)) {
            $rs = $kaen;
        } else if (in_array($kaen, $l2)) {
            $rs = (30 * 60 * 6) - $leftOver; // R6.A0.L0 - leftover
        } else if (in_array($kaen, $l3)) {
            $rs = $leftOver - (30 * 60 * 6); // leftover - R6.A0.L0
        } else if (in_array($kaen, $l4)) {
            $rs = ((30 * 60 * 11) + (60 * 29) + 60) - $leftOver; // R11.A29.L60 - leftover
        }

        return new LastLeftOver(
            ((int)floor($rs / (30 * 60))),
            ((int)floor(($rs % (30 * 60)) / (60))),
            ($rs % 60)
        );
    }

    /**
     * @param int $khan
     * @param int $pouichalip
     * @return Phol
     */
    public function getPhol(int $khan, int $pouichalip)
    {
        $multiplicity = 0;
        $chhaya = 0;
        $multiplicities = [35, 32, 27, 22, 13, 5];
        $chhayas = [0, 35, 67, 94, 116, 129];

        switch ($khan) {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $multiplicity = $multiplicities[$khan];
                $chhaya = $chhayas[$khan];
                break;
            default:
                $chhaya = 134;
                break;
        }

        $q = (int)floor(($pouichalip * $multiplicity) / 900);

        return new Phol(
            0,
            ((int)(floor(($q + $chhaya) / 60))),
            (($q + $chhaya) % 60)
        );
    }

    /**
     * ចំនួនថ្ងៃវ័នបត
     * @return NewYearDaySotins[]
     */
    public function getNewYearDaySotins()
    {
        $sotins = $this->getHas366day($this->jsYear - 1) ? [363, 364, 365, 366] : [362, 363, 364, 365]; // សុទិន
        return array_map(function ($sotin) {
            $sunInfo = $this->getSunInfo($sotin);

            $reasey = (int)floor($sunInfo->getSunInaugurationAsLibda() / (30 * 60));
            // អង្សាស្មើសូន្យ គីជាថ្ងៃចូលឆ្នាំ, មួយ ឬ ពីរ ថ្ងៃបន្ទាប់ជាថ្ងៃវ័នបត ហើយ ថ្ងៃចុងក្រោយគីឡើងស័ក
            $angsar = (int)floor(($sunInfo->getSunInaugurationAsLibda() % (30 * 60)) / (60));
            $libda = $sunInfo->getSunInaugurationAsLibda() % 60;

            return new NewYearDaySotins($sotin, $reasey, $angsar, $libda);
        }, $sotins);
    }

    /**
     * @return TimeOfNewYear
     * @throws TimeOfNewYearException
     */
    public function getTimeOfNewYear()
    {
        $sotinNewYear = array_values(array_filter($this->newYearsDaySotins, function (NewYearDaySotins $sotin) {
            return $sotin->getAngsar() == 0;
        }));

        if (is_array($sotinNewYear) && count($sotinNewYear) == 1) {
            $libda = $sotinNewYear[0]->getLibda();
            $minutes = (24 * 60) - ($libda * 24);
            return new TimeOfNewYear(
                (int)floor($minutes / 60),
                ($minutes % 60)
            );
        } else {
            throw new TimeOfNewYearException("Wrong calculation on new years hour. No sotin with angsar = 0");
        }
    }
}
