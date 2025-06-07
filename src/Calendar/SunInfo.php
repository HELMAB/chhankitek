<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

final class SunInfo
{
    public function __construct(
        private int $sunAverageAsLibda,
        private int $khan,
        private int $pouichalip,
        private Phol $phol,
        private int $sunInaugurationAsLibda
    ) {}

    public function getSunAverageAsLibda(): int
    {
        return $this->sunAverageAsLibda;
    }

    public function getKhan(): int
    {
        return $this->khan;
    }

    public function getPouichalip(): int
    {
        return $this->pouichalip;
    }

    public function getPhol(): Phol
    {
        return $this->phol;
    }

    public function getSunInaugurationAsLibda(): int
    {
        return $this->sunInaugurationAsLibda;
    }
}
