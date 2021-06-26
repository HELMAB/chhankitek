<?php

namespace Asorasoft\Chhankitek;

use Carbon\Carbon;

class LunarDate
{
    private $day;
    private $month;
    private $epochMoved;

    /**
     * LunarDate constructor.
     * @param $day
     * @param $month
     * @param $epochMoved
     */
    public function __construct(int $day, int $month, Carbon $epochMoved)
    {
        $this->day = $day;
        $this->month = $month;
        $this->epochMoved = $epochMoved;
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

    /**
     * @return Carbon
     */
    public function getEpochMoved(): Carbon
    {
        return $this->epochMoved;
    }
}
