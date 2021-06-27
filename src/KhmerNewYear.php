<?php

namespace Asorasoft\Chhankitek;

class KhmerNewYear
{
    private $harkun;
    private $kromathopol;
    private $avaman;
    private $bodithey;
    private $has366day; // សុរិយគតិខ្មែរ
    private $isAthikameas; // 13 months
    private $isChantreathimeas; // 30ថ្ងៃនៅខែជេស្ឋ
    private $jesthHas30; // ខែជេស្ឋមាន៣០ថ្ងៃ
    private $dayLerngSak; // ថ្ងៃឡើងស័ក ច័ន្ទ អង្គារ ...
    private $lunarDateLerngSak; // ថ្ងៃទី ខែ ឡើងស័ក
    private $newYearsDaySotins; // សុទិនសម្រាប់គណនាថ្ងៃចូលឆ្នាំ ថ្ងៃវ័នបត និង ថ្ងៃឡើងស័ក
    private $timeOfNewYear; // ម៉ោងទេវតាចុះ

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
    )
    {
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

    /**
     * @return int
     */
    public function getHarkun(): int
    {
        return $this->harkun;
    }

    /**
     * @return int
     */
    public function getKromathopol(): int
    {
        return $this->kromathopol;
    }

    /**
     * @return int
     */
    public function getAvaman(): int
    {
        return $this->avaman;
    }

    /**
     * @return int
     */
    public function getBodithey(): int
    {
        return $this->bodithey;
    }

    /**
     * @return bool
     */
    public function isHas366day(): bool
    {
        return $this->has366day;
    }

    /**
     * @return bool
     */
    public function isAthikameas(): bool
    {
        return $this->isAthikameas;
    }

    /**
     * @return bool
     */
    public function isChantreathimeas(): bool
    {
        return $this->isChantreathimeas;
    }

    /**
     * @return bool
     */
    public function isJesthHas30(): bool
    {
        return $this->jesthHas30;
    }

    /**
     * @return int
     */
    public function getDayLerngSak(): int
    {
        return $this->dayLerngSak;
    }

    /**
     * @return LunarDateLerngSak
     */
    public function getLunarDateLerngSak(): LunarDateLerngSak
    {
        return $this->lunarDateLerngSak;
    }

    /**
     * @return array
     */
    public function getNewYearsDaySotins(): array
    {
        return $this->newYearsDaySotins;
    }

    /**
     * @return TimeOfNewYear
     */
    public function getTimeOfNewYear(): TimeOfNewYear
    {
        return $this->timeOfNewYear;
    }
}
