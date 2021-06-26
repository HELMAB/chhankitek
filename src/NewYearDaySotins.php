<?php

namespace Asorasoft\Chhankitek;

class NewYearDaySotins
{
    private $sotin;
    private $reasey;
    private $angsar;
    private $libda;

    /**
     * NewYearDaySotins constructor.
     * @param int $sotin
     * @param int $reasey
     * @param int $angsar
     * @param int $libda
     */
    public function __construct(int $sotin, int $reasey, int $angsar, int $libda)
    {
        $this->sotin = $sotin;
        $this->reasey = $reasey;
        $this->angsar = $angsar;
        $this->libda = $libda;
    }

    /**
     * @return int
     */
    public function getSotin(): int
    {
        return $this->sotin;
    }

    /**
     * @return int
     */
    public function getReasey(): int
    {
        return $this->reasey;
    }

    /**
     * @return int
     */
    public function getAngsar(): int
    {
        return $this->angsar;
    }

    /**
     * @return int
     */
    public function getLibda(): int
    {
        return $this->libda;
    }
}
