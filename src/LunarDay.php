<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

final class LunarDay
{
    private int $moonCount;

    private int $moonStatus;

    /**
     * LunarDay constructor.
     *
     * @param  int  $moonCount  The day within the moon phase (1–15)
     * @param  int  $moonStatus  Enum-like index for either កើត or រោច
     */
    public function __construct(int $moonCount, int $moonStatus)
    {
        $this->moonCount = $moonCount;
        $this->moonStatus = $moonStatus;
    }

    /**
     * Get the moon day number within the phase.
     */
    public function getMoonCount(): int
    {
        return $this->moonCount;
    }

    /**
     * Get the moon status (e.g., កើត = 0, រោច = 1).
     */
    public function getMoonStatus(): int
    {
        return $this->moonStatus;
    }
}
