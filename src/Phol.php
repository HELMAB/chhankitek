<?php

namespace Asorasoft\Chhankitek;

class Phol
{
    private $reasey;
    private $angsar;
    private $libda;

    /**
     * Phol constructor.
     * @param int $reasey
     * @param int $angsar
     * @param int $libda
     */
    public function __construct(int $reasey, int $angsar, int $libda)
    {
        $this->reasey = $reasey;
        $this->angsar = $angsar;
        $this->libda = $libda;
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
