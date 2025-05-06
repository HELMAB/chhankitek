<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

use Carbon\CarbonImmutable;

final class LunarDate
{
    private int $day;

    private int $month;

    private CarbonImmutable $epochMoved;

    public function __construct(int $day, int $month, CarbonImmutable $epochMoved)
    {
        $this->day = $day;
        $this->month = $month;
        $this->epochMoved = $epochMoved;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getEpochMoved(): CarbonImmutable
    {
        return $this->epochMoved;
    }
}
