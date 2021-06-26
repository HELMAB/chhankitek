<?php

namespace Asorasoft\Chhankitek;

class SunInfo
{
    private $sunAverageAsLibda;
    private $khan;
    private $pouichalip;
    private $phol;
    private $sunInaugurationAsLibda;

    /**
     * SunInfo constructor.
     * @param int $sunAverageAsLibda
     * @param int $khan
     * @param int $pouichalip
     * @param Phol $phol
     * @param int $sunInaugurationAsLibda
     */
    public function __construct(int $sunAverageAsLibda, int $khan, int $pouichalip, Phol $phol, int $sunInaugurationAsLibda)
    {
        $this->sunAverageAsLibda = $sunAverageAsLibda;
        $this->khan = $khan;
        $this->pouichalip = $pouichalip;
        $this->phol = $phol;
        $this->sunInaugurationAsLibda = $sunInaugurationAsLibda;
    }

    /**
     * @return int
     */
    public function getSunAverageAsLibda(): int
    {
        return $this->sunAverageAsLibda;
    }

    /**
     * @return int
     */
    public function getKhan(): int
    {
        return $this->khan;
    }

    /**
     * @return int
     */
    public function getPouichalip(): int
    {
        return $this->pouichalip;
    }

    /**
     * @return Phol
     */
    public function getPhol(): Phol
    {
        return $this->phol;
    }

    /**
     * @return int
     */
    public function getSunInaugurationAsLibda(): int
    {
        return $this->sunInaugurationAsLibda;
    }
}
