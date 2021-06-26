<?php

namespace Asorasoft\Chhankitek;

class LunarDateLerngSak
{
    private $day;
    private $month;

    /**
     * LunarDateLerngSak constructor.
     * @param int $day
     * @param int $month
     */
    public function __construct(int $day, int $month)
    {
        $this->day = $day;
        $this->month = $month;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }
}
