<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Calendar;

final class Constant
{
    /** @var array<string, int> */
    public array $lunarMonths;

    /** @var array<string, int> */
    public array $solarMonths;

    /** @var array<string, int> */
    public array $animalYears;

    /** @var array<string, int> */
    public array $eraYears;

    /** @var array<string, int> */
    public array $moonStatuses;

    /** @var array<int, string> */
    public array $khNewYearMoments;

    /** @var array<string, int> */
    public array $dayOfWeeks;

    /** @var array<string, string> */
    public array $khmerNumbers;

    public function __construct()
    {
        $this->lunarMonths = [];
        $months = explode('_', 'មិគសិរ_បុស្ស_មាឃ_ផល្គុន_ចេត្រ_ពិសាខ_ជេស្ឋ_អាសាឍ_ស្រាពណ៍_ភទ្របទ_អស្សុជ_កត្តិក_បឋមាសាឍ_ទុតិយាសាឍ');
        foreach ($months as $index => $month) {
            $this->lunarMonths[$month] = $index;
        }

        $this->solarMonths = [];
        $months = explode('_', 'មករា_កុម្ភៈ_មីនា_មេសា_ឧសភា_មិថុនា_កក្កដា_សីហា_កញ្ញា_តុលា_វិច្ឆិកា_ធ្នូ');
        foreach ($months as $index => $month) {
            $this->solarMonths[$month] = $index;
        }

        $this->animalYears = [];
        $years = explode('_', 'ជូត_ឆ្លូវ_ខាល_ថោះ_រោង_ម្សាញ់_មមីរ_មមែ_វក_រកា_ច_កុរ');
        foreach ($years as $index => $year) {
            $this->animalYears[$year] = $index;
        }

        $this->eraYears = [];
        $years = explode('_', 'សំរឹទ្ធិស័ក_ឯកស័ក_ទោស័ក_ត្រីស័ក_ចត្វាស័ក_បញ្ចស័ក_ឆស័ក_សប្តស័ក_អដ្ឋស័ក_នព្វស័ក');
        foreach ($years as $index => $year) {
            $this->eraYears[$year] = $index;
        }

        $this->dayOfWeeks = [];
        $dayOfWeeks = ['អាទិត្យ', 'ចន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
        foreach ($dayOfWeeks as $key => $day) {
            $this->dayOfWeeks[$day] = $key;
        }

        $this->khmerNumbers = [
            '0' => '០',
            '1' => '១',
            '2' => '២',
            '3' => '៣',
            '4' => '៤',
            '5' => '៥',
            '6' => '៦',
            '7' => '៧',
            '8' => '៨',
            '9' => '៩',
        ];

        $this->moonStatuses = [
            'កើត' => 0,
            'រោច' => 1,
        ];

        $this->khNewYearMoments = [
            1879 => '12-04-1879 11:36',
            2011 => '14-04-2011 13:12',
            2012 => '14-04-2012 19:11',
            2013 => '14-04-2013 02:12',
            2014 => '14-04-2014 08:07',
            2015 => '14-04-2015 14:02',
            2025 => '14-04-2025 04:48',
        ];
    }

    /** @return array<string, int> */
    public function getLunarMonths(): array
    {
        return $this->lunarMonths;
    }

    /** @return array<string, int> */
    public function getSolarMonths(): array
    {
        return $this->solarMonths;
    }

    /** @return array<string, int> */
    public function getAnimalYears(): array
    {
        return $this->animalYears;
    }

    /** @return array<string, int> */
    public function getEraYears(): array
    {
        return $this->eraYears;
    }

    /** @return array<string, int> */
    public function getMoonStatuses(): array
    {
        return $this->moonStatuses;
    }
}
