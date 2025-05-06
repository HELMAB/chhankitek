<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

final class YearInfo
{
    private $harkun;

    private $kromathopol;

    private $avaman;

    private $bodithey;

    /**
     * YearInfo constructor.
     */
    public function __construct(int $harkun, int $kromathopol, int $avaman, int $bodithey)
    {
        $this->harkun = $harkun;
        $this->kromathopol = $kromathopol;
        $this->avaman = $avaman;
        $this->bodithey = $bodithey;
    }

    public function getHarkun(): int
    {
        return $this->harkun;
    }

    public function getKromathopol(): int
    {
        return $this->kromathopol;
    }

    public function getAvaman(): int
    {
        return $this->avaman;
    }

    public function getBodithey(): int
    {
        return $this->bodithey;
    }

    public function setBodithey(int $bodithey): void
    {
        $this->bodithey = $bodithey;
    }
}
