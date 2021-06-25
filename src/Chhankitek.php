<?php

namespace Asorasoft\Chhankitek;

/**
 * Class Chhankitek
 * @package Asorasoft\Chhankitek
 */
class Chhankitek
{
    public $bodithey;
    public $avoman;
    public $aharkun;
    public $kromthupul;
    public $aharkunmod;
    public $boditheyLeap;
    public $protetinLeap;
    public $numberOfDaysInKhmerMonth;
    public $numberOfDaysInKhmerYear;
    public $numberOfDaysInGregorianYear;
    public $bEYear; // BE year
    public $maybeBEYear;
    public $mohaSakarajYear;
    public $jolakSakarajYear;
    public $khmerLunarDay;
    public $animalYear;
    public $constant;

    public function __construct()
    {
        $this->constant = new Constant();
    }

    /**
     * @return mixed
     */
    public function getBodithey()
    {
        return $this->bodithey;
    }

    /**
     * @param mixed $bodithey
     */
    public function setBodithey($bodithey): void
    {
        $this->bodithey = $bodithey;
    }

    /**
     * @return mixed
     */
    public function getAvoman()
    {
        return $this->avoman;
    }

    /**
     * @param mixed $avoman
     */
    public function setAvoman($avoman): void
    {
        $this->avoman = $avoman;
    }

    /**
     * @return mixed
     */
    public function getAharkun()
    {
        return $this->aharkun;
    }

    /**
     * @param mixed $aharkun
     */
    public function setAharkun($aharkun): void
    {
        $this->aharkun = $aharkun;
    }

    /**
     * @return mixed
     */
    public function getKromthupul()
    {
        return $this->kromthupul;
    }

    /**
     * @param mixed $kromthupul
     */
    public function setKromthupul($kromthupul): void
    {
        $this->kromthupul = $kromthupul;
    }

    /**
     * @return mixed
     */
    public function getAharkunmod()
    {
        return $this->aharkunmod;
    }

    /**
     * @param mixed $aharkunmod
     */
    public function setAharkunmod($aharkunmod): void
    {
        $this->aharkunmod = $aharkunmod;
    }

    /**
     * @return mixed
     */
    public function getBoditheyLeap()
    {
        return $this->boditheyLeap;
    }

    /**
     * @param mixed $boditheyLeap
     */
    public function setBoditheyLeap($boditheyLeap): void
    {
        $this->boditheyLeap = $boditheyLeap;
    }

    /**
     * @return mixed
     */
    public function getProtetinLeap()
    {
        return $this->protetinLeap;
    }

    /**
     * @param mixed $protetinLeap
     */
    public function setProtetinLeap($protetinLeap): void
    {
        $this->protetinLeap = $protetinLeap;
    }

    /**
     * @return mixed
     */
    public function getNumberOfDaysInKhmerMonth()
    {
        return $this->numberOfDaysInKhmerMonth;
    }

    /**
     * @param mixed $numberOfDaysInKhmerMonth
     */
    public function setNumberOfDaysInKhmerMonth($numberOfDaysInKhmerMonth): void
    {
        $this->numberOfDaysInKhmerMonth = $numberOfDaysInKhmerMonth;
    }

    /**
     * @return mixed
     */
    public function getNumberOfDaysInKhmerYear()
    {
        return $this->numberOfDaysInKhmerYear;
    }

    /**
     * @param mixed $numberOfDaysInKhmerYear
     */
    public function setNumberOfDaysInKhmerYear($numberOfDaysInKhmerYear): void
    {
        $this->numberOfDaysInKhmerYear = $numberOfDaysInKhmerYear;
    }

    /**
     * @return mixed
     */
    public function getNumberOfDaysInGregorianYear()
    {
        return $this->numberOfDaysInGregorianYear;
    }

    /**
     * @param mixed $numberOfDaysInGregorianYear
     */
    public function setNumberOfDaysInGregorianYear($numberOfDaysInGregorianYear): void
    {
        $this->numberOfDaysInGregorianYear = $numberOfDaysInGregorianYear;
    }

    /**
     * @return mixed
     */
    public function getBEYear()
    {
        return $this->bEYear;
    }

    /**
     * @param mixed $bEYear
     */
    public function setBEYear($bEYear): void
    {
        $this->bEYear = $bEYear;
    }

    /**
     * @return mixed
     */
    public function getMaybeBEYear()
    {
        return $this->maybeBEYear;
    }

    /**
     * @param mixed $maybeBEYear
     */
    public function setMaybeBEYear($maybeBEYear): void
    {
        $this->maybeBEYear = $maybeBEYear;
    }

    /**
     * @return mixed
     */
    public function getMohaSakarajYear()
    {
        return $this->mohaSakarajYear;
    }

    /**
     * @param mixed $mohaSakarajYear
     */
    public function setMohaSakarajYear($mohaSakarajYear): void
    {
        $this->mohaSakarajYear = $mohaSakarajYear;
    }

    /**
     * @return mixed
     */
    public function getJolakSakarajYear()
    {
        return $this->jolakSakarajYear;
    }

    /**
     * @param mixed $jolakSakarajYear
     */
    public function setJolakSakarajYear($jolakSakarajYear): void
    {
        $this->jolakSakarajYear = $jolakSakarajYear;
    }

    /**
     * @return mixed
     */
    public function getKhmerLunarDay()
    {
        return $this->khmerLunarDay;
    }

    /**
     * @param mixed $khmerLunarDay
     */
    public function setKhmerLunarDay($khmerLunarDay): void
    {
        $this->khmerLunarDay = $khmerLunarDay;
    }

    /**
     * @return mixed
     */
    public function getAnimalYear()
    {
        return $this->animalYear;
    }

    /**
     * @param mixed $animalYear
     */
    public function setAnimalYear($animalYear): void
    {
        $this->animalYear = $animalYear;
    }

    /**
     * @return Constant
     */
    public function getConstant(): Constant
    {
        return $this->constant;
    }
}
