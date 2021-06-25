<?php

namespace Asorasoft\Chhankitek;

class Constant
{
    public $lunarMonths;
    public $solarMonths;
    public $animalYears;
    public $eraYears;
    public $moonStatuses;
    public $exceptionKhmerYear;

    public function __construct()
    {
        $this->lunarMonths = [];
        $months = explode("_", "មិគសិរ_បុស្ស_មាឃ_ផល្គុន_ចេត្រ_ពិសាខ_ជេស្ឋ_អាសាឍ_ស្រាពណ៍_ភទ្របទ_អស្សុជ_កក្ដិក_បឋមាសាឍ_ទុតិយាសាឍ");
        foreach ($months as $month) {
            array_push($this->lunarMonths, $month);
        }

        $this->solarMonths = [];
        $months = explode("_", "មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ");
        foreach ($months as $month) {
            array_push($this->solarMonths, $months);
        }

        $this->animalYears = [];
        $years = explode("_", "ជូត_ឆ្លូវ_ខាល_ថោះ_រោង_ម្សាញ់_មមីរ_មមែ_វក_រកា_ច_កុរ");
        foreach ($years as $year) {
            array_push($this->animalYears, $year);
        }

        $this->eraYears = [];
        $years = explode("_", "សំរឹទ្ធិស័ក_ឯកស័ក_ទោស័ក_ត្រីស័ក_ចត្វាស័ក_បញ្ចស័ក_ឆស័ក_សប្តស័ក_អដ្ឋស័ក_នព្វស័ក");
        foreach ($years as $year) {
            array_push($this->eraYears, $year);
        }

        $this->moonStatuses = ['កើត', 'រោជ'];

        $this->exceptionKhmerYear = [];
    }

    /**
     * @return array
     */
    public function getLunarMonths(): array
    {
        return $this->lunarMonths;
    }

    /**
     * @return array
     */
    public function getSolarMonths(): array
    {
        return $this->solarMonths;
    }

    /**
     * @return array
     */
    public function getAnimalYears(): array
    {
        return $this->animalYears;
    }

    /**
     * @return array
     */
    public function getEraYears(): array
    {
        return $this->eraYears;
    }

    /**
     * @return mixed
     */
    public function getMoonStatuses()
    {
        return $this->moonStatuses;
    }

    /**
     * @return array
     */
    public function getExceptionKhmerYear(): array
    {
        return $this->exceptionKhmerYear;
    }
}
