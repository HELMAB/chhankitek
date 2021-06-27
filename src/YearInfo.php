<?php

namespace Asorasoft\Chhankitek;

class YearInfo
{
    private $harkun;
    private $kromathopol;
    private $avaman;
    private $bodithey;

    /**
     * YearInfo constructor.
     * @param int $harkun
     * @param int $kromathopol
     * @param int $avaman
     * @param int $bodithey
     */
    public function __construct(int $harkun, int $kromathopol, int $avaman, int $bodithey)
    {
        $this->harkun = $harkun;
        $this->kromathopol = $kromathopol;
        $this->avaman = $avaman;
        $this->bodithey = $bodithey;
    }

    /**
     * @return int
     */
    public function getHarkun(): int
    {
        return $this->harkun;
    }

    /**
     * @return int
     */
    public function getKromathopol(): int
    {
        return $this->kromathopol;
    }

    /**
     * @return int
     */
    public function getAvaman(): int
    {
        return $this->avaman;
    }

    /**
     * @return int
     */
    public function getBodithey(): int
    {
        return $this->bodithey;
    }

    /**
     * @param int $bodithey
     */
    public function setBodithey(int $bodithey): void
    {
        $this->bodithey = $bodithey;
    }
}
