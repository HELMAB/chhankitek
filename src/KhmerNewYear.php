<?php

namespace Asorasoft\Chhankitek;

class KhmerNewYear
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
     * @param int $harkun
     * @param int $kromathopol
     * @param int $avaman
     * @param int $bodithey
     * @param bool $has366day
     * @param bool $isAthikameas
     * @param bool $isChantreathimeas
     * @param bool $jesthHas30
     * @param int $dayLerngSak
     * @param LunarDateLerngSak $lunarDateLerngSak
     * @param array $newYearsDaySotins
     * @param TimeOfNewYear $timeOfNewYear
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
     * សុរិយគតិខ្មែរ
     * @return bool
     */
    public function isHas366day(): bool
    {
        return $this->has366day;
    }

    /**
     * 13 months
     * @return bool
     */
    public function isAthikameas(): bool
    {
        return $this->isAthikameas;
    }

    /**
     * 30ថ្ងៃនៅខែជេស្ឋ
     * @return bool
     */
    public function isChantreathimeas(): bool
    {
        return $this->isChantreathimeas;
    }

    /**
     * ខែជេស្ឋមាន៣០ថ្ងៃ
     * @return bool
     */
    public function isJesthHas30(): bool
    {
        return $this->jesthHas30;
    }

    /**
     * ថ្ងៃឡើងស័ក ច័ន្ទ អង្គារ ...
     * @return int
     */
    public function getDayLerngSak(): int
    {
        return $this->dayLerngSak;
    }

    /**
     * ថ្ងៃទី ខែ ឡើងស័ក
     * @return LunarDateLerngSak
     */
    public function getLunarDateLerngSak(): LunarDateLerngSak
    {
        return $this->lunarDateLerngSak;
    }

    /**
     * សុទិនសម្រាប់គណនាថ្ងៃចូលឆ្នាំ ថ្ងៃវ័នបត និង ថ្ងៃឡើងស័ក
     * @return array
     */
    public function getNewYearsDaySotins(): array
    {
        return $this->newYearsDaySotins;
    }

    /**
     * ម៉ោងទេវតាចុះ
     * @return TimeOfNewYear
     */
    public function getTimeOfNewYear(): TimeOfNewYear
    {
        return $this->timeOfNewYear;
    }
}
