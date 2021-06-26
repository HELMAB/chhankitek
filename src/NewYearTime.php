<?php

namespace Asorasoft\Chhankitek;

class NewYearTime
{
    private $hour;
    private $minute;

    /**
     * NewYearTime constructor.
     * @param int $hour
     * @param int $minute
     */
    public function __construct(int $hour, int $minute)
    {
        $this->hour = $hour;
        $this->minute = $minute;
    }

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }
}
