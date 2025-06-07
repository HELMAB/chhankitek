<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

use Asorasoft\Chhankitek\Exception\TimeOfNewYearException;

final class SoriyatraLerngSak
{
    public int $jsYear;

    public YearInfo $info;

    public bool $has366day;

    public bool $isAthikameas;

    public bool $isChantreathimeas;

    public bool $jesthHas30;

    public int $dayLerngSak;

    public LunarDateLerngSak $lunarDateLerngSak;

    /** @var NewYearDaySotins[] */
    public array $newYearsDaySotins;

    public TimeOfNewYear $timeOfNewYear;

    public KhmerNewYear $khmerNewYear;

    /**
     * SoriyatraLerngSak constructor.
     *
     * @throws TimeOfNewYearException
     */
    public function __construct(int $jsYear)
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
     */
    public function getInfo(int $jsYear): YearInfo
    {
        $h = 292207 * $jsYear + 373;
        $harkun = (int) floor($h / 800) + 1;
        $kromathopol = 800 - ($h % 800);

        $a = 11 * $harkun + 650;
        $avaman = $a % 692;
        $bodithey = (int) (($harkun + floor($a / 692)) % 30);

        return new YearInfo($harkun, $kromathopol, $avaman, $bodithey);
    }

    /**
     * ឆ្នាំចុល្លសករាជដែលមាន ៣៦៦ ថ្ងៃ
     */
    public function getHas366day(int $jsYear): bool
    {
        return $this->getInfo($jsYear)->getKromathopol() <= 207;
    }

    /**
     * រកឆ្នាំអធិកមាស (មួយឆ្នាំមាន ១៣ ខែ)
     */
    public function getIsAthikameas(int $jsYear): bool
    {
        $info = $this->getInfo($jsYear);
        $next = $this->getInfo($jsYear + 1);

        return ! ($info->getBodithey() === 25 && $next->getBodithey() === 5)
            && (
                $info->getBodithey() > 24
                || $info->getBodithey() < 6
                || ($info->getBodithey() === 24 && $next->getBodithey() === 6)
            );
    }

    /**
     * រកឆ្នាំចន្ទ្រាធិមាស (Chantreathimeas year check)
     */
    public function getIsChantreathimeas(int $jsYear): bool
    {
        $info = $this->getInfo($jsYear);
        $next = $this->getInfo($jsYear + 1);
        $prev = $this->getInfo($jsYear - 1);
        $has366 = $this->getHas366day($jsYear);

        return ($has366 && $info->getAvaman() < 127)
            || (
                ! ($info->getAvaman() === 137 && $next->getAvaman() === 0)
                && (
                    (! $has366 && $info->getAvaman() < 138)
                    || ($prev->getAvaman() === 137 && $info->getAvaman() === 0)
                )
            );
    }

    /**
     * ឆែកមើលថាជាឆ្នាំដែលខែជេស្ឋមាន៣០ថ្ងៃឬទេ
     */
    public function jesthHas30(): bool
    {
        $hasChantreathimeas = $this->isChantreathimeas;

        if ($this->isAthikameas && $hasChantreathimeas) {
            return false;
        }

        if (
            ! $hasChantreathimeas &&
            $this->getIsAthikameas($this->jsYear - 1) &&
            $this->getIsChantreathimeas($this->jsYear - 1)
        ) {
            return true;
        }

        return $hasChantreathimeas;
    }

    /**
     * គណនារកថ្ងៃឡើងស័ក
     */
    public function getLunarDateLerngSak(): LunarDateLerngSak
    {
        $lunarMonths = (new Constant())->getLunarMonths();
        $bodithey = $this->info->getBodithey();

        if (
            $this->getIsAthikameas($this->jsYear - 1) &&
            $this->getIsChantreathimeas($this->jsYear - 1)
        ) {
            $bodithey = ($bodithey + 1) % 30;
        }

        $day = $bodithey >= 6 ? $bodithey - 1 : $bodithey;
        $month = $bodithey >= 6 ? $lunarMonths['ចេត្រ'] : $lunarMonths['ពិសាខ'];

        return new LunarDateLerngSak($day, $month);
    }

    public function getSunInfo(int $sotin): SunInfo
    {
        $infoOfPreviousYear = $this->getInfo($this->jsYear - 1);

        $sunAverageAsLibda = (int) round($this->getSunAverageAsLibda($sotin, $infoOfPreviousYear));
        $leftOver = (int) round($this->getLeftOver($sunAverageAsLibda));
        $kaen = (int) floor($leftOver / (30 * 60));
        $lastLeftOver = $this->getLastLeftOver($kaen, $leftOver);

        $reasey = $lastLeftOver->getReasey();
        $angsar = $lastLeftOver->getAngsar();
        $libda = $lastLeftOver->getLibda();

        $khan = $angsar >= 15 ? (2 * $reasey + 1) : (2 * $reasey);
        $pouichalip = $angsar >= 15
            ? 60 * ($angsar - 15) + $libda
            : 60 * $angsar + $libda;

        $phol = $this->getPhol($khan, $pouichalip);
        $pholAsLibda = (30 * 60 * $phol->getReasey()) + (60 * $phol->getAngsar()) + $phol->getLibda();

        $sunInaugurationAsLibda = $kaen <= 5
            ? $sunAverageAsLibda - $pholAsLibda
            : $sunAverageAsLibda + $pholAsLibda;

        return new SunInfo(
            $sunAverageAsLibda,
            $khan,
            $pouichalip,
            $phol,
            (int) round($sunInaugurationAsLibda)
        );
    }

    /**
     * គណនាមធ្យមព្រះអាទិត្យ ជា លិប្ដា
     */
    public function getSunAverageAsLibda(int $sotin, YearInfo $info): int
    {
        $r2 = 800 * $sotin + $info->getKromathopol();
        $reasey = (int) floor($r2 / 24350); // រាសី
        $r3 = $r2 % 24350;
        $angsar = (int) floor($r3 / 811); // អង្សា
        $r4 = $r3 % 811;
        $l1 = (int) floor($r4 / 14);
        $libda = $l1 - 3; // លិប្ដា

        return (int) ((30 * 60 * $reasey) + (60 * $angsar) + $libda);
    }

    /**
     * Calculate the remaining Libda after subtracting from the average sun position (R2.A20.L0).
     *
     * If the average sun position is less than R2.A20.L0 (2 zodiac signs and 20 degrees),
     * then 12 zodiac signs (each 1800 Libda) are added to ensure the result is positive.
     *
     * @param  int  $sunAverageAsLibda  Average sun position in Libda
     * @return int Remaining Libda (left over) after adjustment
     *
     * មធ្យមព្រះអាទិត្យ - R2.A20.L0
     */
    public function getLeftOver(int $sunAverageAsLibda): int
    {
        $s1 = (30 * 60 * 2) + (60 * 20); // R2.A20.L0
        $leftOver = $sunAverageAsLibda - $s1;

        // បើតូចជាង ខ្ចី ១២ រាសី
        if ($sunAverageAsLibda < $s1) {
            $leftOver += (30 * 60 * 12);
        }

        return $leftOver;
    }

    /**
     * Calculate the last leftover value based on Kaen and remaining Libda
     */
    public function getLastLeftOver(int $kaen, int $leftOver): LastLeftOver
    {
        $rs = match (true) {
            in_array($kaen, [0, 1, 2], true) => $kaen,
            in_array($kaen, [3, 4, 5], true) => (30 * 60 * 6) - $leftOver,
            in_array($kaen, [6, 7, 8], true) => $leftOver - (30 * 60 * 6),
            in_array($kaen, [9, 10, 11], true) => ((30 * 60 * 11) + (60 * 29) + 60) - $leftOver,
            default => 0, // fallback; or consider throwing an exception if kaen is invalid
        };

        return new LastLeftOver(
            (int) floor($rs / (30 * 60)),
            (int) floor(($rs % (30 * 60)) / 60),
            $rs % 60
        );
    }

    /**
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

        $q = (int) floor(($pouichalip * $multiplicity) / 900);

        return new Phol(
            0,
            ((int) (floor(($q + $chhaya) / 60))),
            (($q + $chhaya) % 60)
        );
    }

    /**
     * ចំនួនថ្ងៃវ័នបត
     *
     * @return NewYearDaySotins[]
     */
    public function getNewYearDaySotins()
    {
        $sotins = $this->getHas366day($this->jsYear - 1) ? [363, 364, 365, 366] : [362, 363, 364, 365]; // សុទិន

        return array_map(function ($sotin) {
            $sunInfo = $this->getSunInfo($sotin);

            $reasey = (int) floor($sunInfo->getSunInaugurationAsLibda() / (30 * 60));
            // អង្សាស្មើសូន្យ គីជាថ្ងៃចូលឆ្នាំ, មួយ ឬ ពីរ ថ្ងៃបន្ទាប់ជាថ្ងៃវ័នបត ហើយ ថ្ងៃចុងក្រោយគីឡើងស័ក
            $angsar = (int) floor(($sunInfo->getSunInaugurationAsLibda() % (30 * 60)) / (60));
            $libda = $sunInfo->getSunInaugurationAsLibda() % 60;

            return new NewYearDaySotins($sotin, $reasey, $angsar, $libda);
        }, $sotins);
    }

    /**
     * Calculate the exact time of Khmer New Year (hour and minute).
     *
     * Finds the sotin (solar position) where angle (angsar) is 0, which marks the new year.
     * Converts the Libda value to minutes and subtracts from 24h to determine the hour/minute of transition.
     *
     *
     * @throws TimeOfNewYearException If no valid sotin with angsar = 0 is found.
     *
     * ចំនួននាទីនៃការចូលឆ្នាំ ដែលស្ថិតនៅលើសុទិនដែលមានអង្សាស្មើសូន្យ
     */
    public function getTimeOfNewYear(): TimeOfNewYear
    {
        $sotinNewYear = array_values(array_filter(
            $this->newYearsDaySotins,
            fn (NewYearDaySotins $sotin) => $sotin->getAngsar() === 0
        ));

        if (count($sotinNewYear) === 1) {
            $libda = $sotinNewYear[0]->getLibda();
            $minutes = (24 * 60) - ($libda * 24);

            return new TimeOfNewYear(
                (int) floor($minutes / 60),
                $minutes % 60
            );
        }

        throw new TimeOfNewYearException('Wrong calculation on new years hour. No sotin with angsar = 0');
    }
}
