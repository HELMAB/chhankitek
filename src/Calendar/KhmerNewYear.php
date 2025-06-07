<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

final class KhmerNewYear
{
    private $harkun;

    private $kromathopol;

    private $avaman;

    private $bodithey;

    private $has366day;

    private $isAthikameas;

    private $isChantreathimeas;

    private $jesthHas30;

    private $dayLerngSak;

    private $lunarDateLerngSak;

    private $newYearsDaySotins;

    private $timeOfNewYear;

    /**
     * KhmerNewYear constructor.
     */
    public function __construct(
        int $harkun,
        int $kromathopol,
        int $avaman,
        int $bodithey,
        bool $has366day,
        bool $isAthikameas,
        bool $isChantreathimeas,
        bool $jesthHas30,
        int $dayLerngSak,
        LunarDateLerngSak $lunarDateLerngSak,
        array $newYearsDaySotins,
        TimeOfNewYear $timeOfNewYear
    ) {
        $this->harkun = $harkun;
        $this->kromathopol = $kromathopol;
        $this->avaman = $avaman;
        $this->bodithey = $bodithey;
        $this->has366day = $has366day;
        $this->isAthikameas = $isAthikameas;
        $this->isChantreathimeas = $isChantreathimeas;
        $this->jesthHas30 = $jesthHas30;
        $this->dayLerngSak = $dayLerngSak;
        $this->lunarDateLerngSak = $lunarDateLerngSak;
        $this->newYearsDaySotins = $newYearsDaySotins;
        $this->timeOfNewYear = $timeOfNewYear;
    }

    public function getHarkun(): int
    {
        return $this->harkun;
    }

    public function getKromathopol(): int
    {
        return $this->kromathopol;
    }

    public function getAvaman(): int
    {
        return $this->avaman;
    }

    public function getBodithey(): int
    {
        return $this->bodithey;
    }

    /**
     * សុរិយគតិខ្មែរ
     */
    public function isHas366day(): bool
    {
        return $this->has366day;
    }

    /**
     * 13 months
     */
    public function isAthikameas(): bool
    {
        return $this->isAthikameas;
    }

    /**
     * 30ថ្ងៃនៅខែជេស្ឋ
     */
    public function isChantreathimeas(): bool
    {
        return $this->isChantreathimeas;
    }

    /**
     * ខែជេស្ឋមាន៣០ថ្ងៃ
     */
    public function isJesthHas30(): bool
    {
        return $this->jesthHas30;
    }

    /**
     * ថ្ងៃឡើងស័ក ច័ន្ទ អង្គារ ...
     */
    public function getDayLerngSak(): int
    {
        return $this->dayLerngSak;
    }

    /**
     * ថ្ងៃទី ខែ ឡើងស័ក
     */
    public function getLunarDateLerngSak(): LunarDateLerngSak
    {
        return $this->lunarDateLerngSak;
    }

    /**
     * សុទិនសម្រាប់គណនាថ្ងៃចូលឆ្នាំ ថ្ងៃវ័នបត និង ថ្ងៃឡើងស័ក
     */
    public function getNewYearsDaySotins(): array
    {
        return $this->newYearsDaySotins;
    }

    /**
     * ម៉ោងទេវតាចុះ
     */
    public function getTimeOfNewYear(): TimeOfNewYear
    {
        return $this->timeOfNewYear;
    }
}
