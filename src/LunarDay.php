<?php

namespace Asorasoft\Chhankitek;

class LunarDay
{
    private $moonCount;
    private $moonStatus;

    /**
     * LunarDay constructor.
     * @param int $moonCount
     * @param int $moonStatus
     */
    public function __construct(int $moonCount, int $moonStatus)
    {
        $this->moonCount = $moonCount;
        $this->moonStatus = $moonStatus;
    }

    /**
     * @return mixed
     */
    public function getMoonCount()
    {
        return $this->moonCount;
    }

    /**
     * @return mixed
     */
    public function getMoonStatus()
    {
        return $this->moonStatus;
    }
}
