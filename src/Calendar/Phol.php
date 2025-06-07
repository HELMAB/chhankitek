<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

final class Phol
{
    private $reasey;

    private $angsar;

    private $libda;

    /**
     * Phol constructor.
     */
    public function __construct(int $reasey, int $angsar, int $libda)
    {
        $this->reasey = $reasey;
        $this->angsar = $angsar;
        $this->libda = $libda;
    }

    public function getReasey(): int
    {
        return $this->reasey;
    }

    public function getAngsar(): int
    {
        return $this->angsar;
    }

    public function getLibda(): int
    {
        return $this->libda;
    }
}
