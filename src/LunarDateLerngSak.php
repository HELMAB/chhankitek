<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

final class LunarDateLerngSak
{
    private $day;

    private $month;

    /**
     * LunarDateLerngSak constructor.
     */
    public function __construct(int $day, int $month)
    {
        $this->day = $day;
        $this->month = $month;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getMonth(): int
    {
        return $this->month;
    }
}
