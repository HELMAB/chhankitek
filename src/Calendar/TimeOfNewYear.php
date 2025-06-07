<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

final class TimeOfNewYear
{
    private $hour;

    private $minute;

    /**
     * NewYearTime constructor.
     */
    public function __construct(int $hour, int $minute)
    {
        $this->hour = $hour;
        $this->minute = $minute;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }
}
