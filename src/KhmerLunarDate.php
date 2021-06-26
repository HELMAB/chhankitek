<?php

namespace Asorasoft\Chhankitek;

class KhmerLunarDate
{
    private $dayOfWeek;
    private $lunarDay;
    private $lunarMonth;
    private $lunarZodiac;
    private $lunarEra;
    private $lunarYear;

    /**
     * KhmerLunarDate constructor.
     * @param string $dayOfWeek
     * @param string $lunarDay
     * @param string $lunarMonth
     * @param string $lunarZodiac
     * @param string $lunarEra
     * @param string $lunarYear
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

    /**
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * @return string
     */
    public function getLunarDay(): string
    {
        return $this->lunarDay;
    }

    /**
     * @return string
     */
    public function getLunarMonth(): string
    {
        return $this->lunarMonth;
    }

    /**
     * @return string
     */
    public function getLunarZodiac(): string
    {
        return $this->lunarZodiac;
    }

    /**
     * @return string
     */
    public function getLunarEra(): string
    {
        return $this->lunarEra;
    }

    /**
     * @return string
     */
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
