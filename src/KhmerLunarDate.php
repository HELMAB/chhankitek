<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

final class KhmerLunarDate
{
    private $dayOfWeek;

    private $lunarDay;

    private $lunarMonth;

    private $lunarZodiac;

    private $lunarEra;

    private $lunarYear;

    /**
     * KhmerLunarDate constructor.
     */
    public function __construct(string $dayOfWeek, string $lunarDay, string $lunarMonth, string $lunarZodiac, string $lunarEra, string $lunarYear)
    {
        $this->dayOfWeek = $dayOfWeek;
        $this->lunarDay = $lunarDay;
        $this->lunarMonth = $lunarMonth;
        $this->lunarZodiac = $lunarZodiac;
        $this->lunarEra = $lunarEra;
        $this->lunarYear = $lunarYear;
    }

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    public function getLunarDay(): string
    {
        return $this->lunarDay;
    }

    public function getLunarMonth(): string
    {
        return $this->lunarMonth;
    }

    public function getLunarZodiac(): string
    {
        return $this->lunarZodiac;
    }

    public function getLunarEra(): string
    {
        return $this->lunarEra;
    }

    public function getLunarYear(): string
    {
        return $this->lunarYear;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return "ថ្ងៃ$this->dayOfWeek $this->lunarDay ខែ$this->lunarMonth ឆ្នាំ$this->lunarZodiac $this->lunarEra ពុទ្ធសករាជ $this->lunarYear";
    }
}
