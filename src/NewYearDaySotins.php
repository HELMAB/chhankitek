<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

final class NewYearDaySotins
{
    private $sotin;

    private $reasey;

    private $angsar;

    private $libda;

    /**
     * NewYearDaySotins constructor.
     */
    public function __construct(int $sotin, int $reasey, int $angsar, int $libda)
    {
        $this->sotin = $sotin;
        $this->reasey = $reasey;
        $this->angsar = $angsar;
        $this->libda = $libda;
    }

    public function getSotin(): int
    {
        return $this->sotin;
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
