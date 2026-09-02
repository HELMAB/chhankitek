<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

use Asorasoft\Chhankitek\Cache\ArrayCache;
use Asorasoft\Chhankitek\Cache\CacheRepository;
use Asorasoft\Chhankitek\Cache\LaravelCache;
use Asorasoft\Chhankitek\Calendar\Constant;
use Asorasoft\Chhankitek\Calendar\KhmerLunarDate;
use Asorasoft\Chhankitek\Calendar\LunarDate;
use Asorasoft\Chhankitek\Calendar\LunarDay;
use Asorasoft\Chhankitek\Calendar\SoriyatraLerngSak;
use Asorasoft\Chhankitek\Exception\InvalidKhmerMonthException;
use Asorasoft\Chhankitek\Exception\TimeOfNewYearException;
use Asorasoft\Chhankitek\Exception\VisakhabocheaException;
use Asorasoft\Chhankitek\Traits\HasKhmerNumberConversion;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Class Chhankitek
 */
final class Chhankitek
{
    use HasKhmerNumberConversion;

    public CarbonImmutable $target;

    public mixed $formatKhmerDate;

    public CarbonImmutable $khNewYearDateTime;

    private CacheRepository $cache;

    public function __construct(CarbonImmutable $target, ?CacheRepository $cache = null)
    {
        $formatted = CarbonImmutable::createFromFormat('!d/m/Y', $target->format('d/m/Y'), 'Asia/Phnom_Penh');

        if (! $formatted instanceof CarbonImmutable) {
            throw new InvalidArgumentException('Failed to create CarbonImmutable from given date.');
        }

        $this->cache = $cache ?? $this->resolveDefaultCache();
        $this->target = $formatted;
        $this->formatKhmerDate = $this->khmerLunarDate($this->target);

        /** @TODO needs to recheck khmer new year date time calculation */
        // $this->khNewYearDateTime = $this->getKhmerNewYearDateTime($this->target->year);
    }

    /**
     * Bodithey: បូតិថី
     * Bodithey determines if a given beYear is a leap-month year.
     * Given year target year in Buddhist Era. Return (0-29)
     */
    public function getBodithey(int $beYear): int
    {
        $ahk = $this->getAharkun($beYear);
        $avml = (int) (floor((11 * $ahk + 25) / 692));
        $m = $avml + $ahk + 29;

        return $m % 30;
    }

    /**
     * Avoman: អាវមាន
     * Avoman determines if a given year is a leap-day year.
     * Given a year in Buddhist Era as denoted as adYear. Return (0 - 691)
     */
    public function getAvoman(int $beYear): int
    {
        $ahk = $this->getAharkun($beYear);
        $avm = (11 * $ahk + 25) % 692;

        return $avm;
    }

    /**
     * Calculate Aharkun (អាហារគុណ ឬ ហារគុណ) for a given Buddhist Era (BE) year.
     *
     * Aharkun is a critical value in the Khmer lunar calendar, used to determine
     * leap months (Bodithey) and leap days (Avoman). It is based on traditional
     * Khmer astronomical constants.
     *
     * Calculation:
     *   Aharkun = floor((BE * 292207 + 499) / 800) + 4
     *
     * @param  int  $beYear  Buddhist Era year (e.g., 2569)
     * @return int The calculated Aharkun value
     */
    public function getAharkun(int $beYear): int
    {
        $solarMonthsSinceEpoch = ($beYear * 292207) + 499;
        $baseAharkun = (int) floor($solarMonthsSinceEpoch / 800);

        return $baseAharkun + 4;
    }

    /**
     * Calculate the Kromathupul (ក្រមធុបុល) value for a given Buddhist Era (BE) year.
     *
     * Kromathupul is used to determine whether a Khmer solar year is a leap year.
     * It is calculated by subtracting the Aharkun modulus (remainder when divided by 800) from 800.
     *
     * @param  int  $beYear  The Buddhist Era year to evaluate
     * @return int The resulting Kromathupul value
     */
    public function kromthupul(int $beYear): int
    {
        $aharkunMod = $this->getAharkunMod($beYear);

        return 800 - $aharkunMod;
    }

    /**
     * Determine whether the given Khmer solar year is a leap year.
     *
     * A Khmer solar year is considered a leap year if the calculated Kromathupul
     * value is less than or equal to 207.
     *
     * @param  int  $beYear  The Buddhist Era year to check
     * @return bool True if it's a solar leap year, false otherwise
     */
    public function isKhmerSolarLeap(int $beYear): bool
    {
        return $this->kromthupul($beYear) <= 207;
    }

    /**
     * Calculate the Aharkun modulus value for a given Buddhist Era (BE) year.
     *
     * This value is used in Khmer calendar calculations to derive Kromathupul and assess
     * leap year conditions in the solar calendar. It is calculated as:
     *
     *     (BE * 292207 + 499) % 800
     *
     * @param  int  $beYear  Buddhist Era year
     * @return int The calculated Aharkun modulus value
     */
    public function getAharkunMod(int $beYear): int
    {
        return (($beYear * 292207) + 499) % 800;
    }

    /**
     * Determine the leap status of a given Khmer Buddhist Era (BE) year.
     *
     * A Khmer lunar year can be:
     * - Regular year: 12 months, 354 days
     * - Leap month year: 13 months (adds បឋមាសាឍ)
     * - Leap day year: extra day in month of ជេស្ឋ (making it 30 days)
     * - Both: in rare cases, rules define one component to be applied, not both simultaneously
     *
     * Returns:
     *  - 0: Regular year
     *  - 1: Leap month only
     *  - 2: Leap day only
     *  - 3: Leap month and day (intermediate result; handled in getProtetinLeap)
     *
     * @param  int  $beYear  Buddhist Era year
     */
    public function getBoditheyLeap(int $beYear): int
    {
        $avoman = $this->getAvoman($beYear);
        $bodithey = $this->getBodithey($beYear);

        $boditheyLeap = ($bodithey >= 25 || $bodithey <= 5) ? 1 : 0;

        $avomanLeap = 0;
        if ($this->isKhmerSolarLeap($beYear)) {
            if ($avoman <= 126) {
                $avomanLeap = 1;
            }
        } else {
            if ($avoman <= 137) {
                // Ensure 137 is not the start of a leap sequence
                $avomanLeap = ($this->getAvoman($beYear + 1) !== 0) ? 1 : 0;
            }
        }

        // Handle exception: consecutive 25/5 — only allow 5 as leap
        if ($bodithey === 25 && $this->getBodithey($beYear + 1) === 5) {
            $boditheyLeap = 0;
        }

        // Handle exception: consecutive 24/6 — enforce leap month for 24
        if ($bodithey === 24 && $this->getBodithey($beYear + 1) === 6) {
            $boditheyLeap = 1;
        }

        return match (true) {
            $boditheyLeap === 1 && $avomanLeap === 1 => 3,
            $boditheyLeap === 1 => 1,
            $avomanLeap === 1 => 2,
            default => 0,
        };
    }

    /**
     * Determine the protetin leap status for a given Khmer Buddhist Era (BE) year.
     *
     * Khmer calendar rules do not allow both leap-month and leap-day in the same year.
     * If both conditions occur, only the leap-month applies in the current year,
     * and the leap-day is deferred to the following year.
     *
     * Returns:
     *  - 0: Regular year
     *  - 1: Leap month
     *  - 2: Leap day (only, or deferred from previous year with both)
     */
    public function getProtetinLeap(int $beYear): int
    {
        $leapType = $this->getBoditheyLeap($beYear);

        return match (true) {
            $leapType === 3 => 1, // Prioritize leap month if both present
            $leapType === 2, $leapType === 1 => $leapType,
            $this->getBoditheyLeap($beYear - 1) === 3 => 2, // Deferred leap-day
            default => 0,
        };
    }

    /**
     * Get the maximum number of days in a given Khmer lunar month for a specific BE year.
     *
     * Rules:
     * - If the month is "Jyeshtha" (ជេស្ឋ) and it's a leap-day year, it has 30 days.
     * - If the month is either "Adhikameas 1" (បឋមាសាឍ) or "Adhikameas 2" (ទុតិយាសាឍ), it has 30 days.
     * - Otherwise, months alternate: odd months have 30 days, even months have 29.
     *
     * @param  int  $beMonth  Khmer month index
     * @param  int  $beYear  Buddhist Era year
     * @return int Number of days in the month
     */
    public function getNumberOfDayInKhmerMonth(int $beMonth, int $beYear): int
    {
        $lunarMonths = (new Constant)->getLunarMonths();

        if ($beMonth === $lunarMonths['ជេស្ឋ'] && $this->isKhmerLeapDay($beYear)) {
            return 30;
        }

        if (
            $beMonth === $lunarMonths['បឋមាសាឍ'] ||
            $beMonth === $lunarMonths['ទុតិយាសាឍ']
        ) {
            return 30;
        }

        // Even-numbered months have 29 days; odd-numbered months have 30
        return $beMonth % 2 === 0 ? 29 : 30;
    }

    /**
     * Get the total number of days in a Khmer lunar year.
     *
     * - Regular years have 354 days.
     * - Leap-day years (Chhantrea Thimeas) have 355 days.
     * - Leap-month years (Adhikameas) have 384 days.
     *
     * @param  int  $beYear  Buddhist Era year
     * @return int Total number of days in the year
     */
    public function getNumberOfDayInKhmerYear(int $beYear): int
    {
        if ($this->isKhmerLeapMonth($beYear)) {
            return 384;
        }

        if ($this->isKhmerLeapDay($beYear)) {
            return 355;
        }

        return 354;
    }

    /**
     * Get the total number of days in a Gregorian year.
     *
     * Leap years have 366 days, while common years have 365 days.
     *
     * @param  int  $adYear  Gregorian year
     * @return int Total number of days in the year
     */
    public function getNumberOfDayInGregorianYear(int $adYear): int
    {
        return $this->isGregorianLeap($adYear) ? 366 : 365;
    }

    /**
     * Determine if the given Buddhist Era (BE) year is a leap month year (Adhikameas).
     *
     * A leap month year has an additional lunar month and totals 384 days.
     *
     * @param  int  $beYear  Buddhist Era year
     * @return bool True if the year includes an extra month, otherwise false
     */
    public function isKhmerLeapMonth(int $beYear): bool
    {
        return $this->getProtetinLeap($beYear) === 1;
    }

    /**
     * Determine if the given Buddhist Era (BE) year is a leap day year.
     *
     * A leap day year, known as Chhantrea Thimeas (ចន្ទ្រាធិមាស) or Adhikavereak (អធិកវារៈ),
     * has one extra lunar day and totals 355 days.
     *
     * @param  int  $beYear  Buddhist Era year
     * @return bool True if the year includes an extra day, otherwise false
     */
    public function isKhmerLeapDay(int $beYear): bool
    {
        return $this->getProtetinLeap($beYear) === 2;
    }

    /**
     * Determine if a given Gregorian year is a leap year.
     *
     * A Gregorian leap year occurs:
     * - Every 4 years,
     * - Except years that are divisible by 100,
     * - Unless they are also divisible by 400.
     *
     * @param  int  $adYear  Gregorian year
     * @return bool True if leap year, false otherwise
     */
    public function isGregorianLeap(int $adYear): bool
    {
        return ($adYear % 4 === 0 && $adYear % 100 !== 0) || ($adYear % 400 === 0);
    }

    /**
     * រកថ្ងៃវិសាខបូជា
     * ថ្ងៃដាច់ឆ្នាំពុទ្ធសករាជ
     *
     * Finds Visakha Bochea day, which falls on the 14th waxing moon of the month of ពិសាខ.
     *
     * @param  int  $gregorianYear  The Gregorian year to search within
     * @return CarbonImmutable The date of Visakha Bochea in the given year
     *
     * @throws InvalidKhmerMonthException
     * @throws VisakhabocheaException
     */
    public function getVisakhaBochea(int $gregorianYear): CarbonImmutable
    {
        $lunarMonths = (new Constant)->lunarMonths;
        $date = CarbonImmutable::createFromFormat('!d/m/Y', "1/1/{$gregorianYear}", 'Asia/Phnom_Penh');

        $cached = $this->cache->remember("chhakitek_visakha_bochea_{$date}", 60 * 60 * 24 * 365, function () use ($date, $lunarMonths) {
            for ($i = 0; $i < 365; $i++) {
                $lunarDate = $this->findLunarDate($date);
                if ($lunarDate->getMonth() === $lunarMonths['ពិសាខ'] && $lunarDate->getDay() === 14) {
                    // Store as a string to avoid __PHP_Incomplete_Class on deserialization.
                    return $lunarDate->getEpochMoved()->toIso8601String();
                }

                $date = $date->addDay();
            }

            throw new VisakhabocheaException('Cannot find Visakhabochea day.');
        });

        return CarbonImmutable::parse($cached, 'Asia/Phnom_Penh');
    }

    /**
     * Calculate the Buddhist Era (BE) year for a given Gregorian date.
     *
     * The BE year changes after Visakha Bochea day, which is the 14th waxing moon of the month of ពិសាខ.
     * If the given date is after Visakha Bochea, the BE year is incremented.
     *
     * @param  CarbonImmutable  $target  The date to evaluate
     * @return int The corresponding Buddhist Era year
     *
     * @throws InvalidKhmerMonthException
     * @throws VisakhabocheaException
     */
    public function getBEYear(CarbonImmutable $target): int
    {
        $visakha = $this->getVisakhaBochea($target->year);

        if ($visakha->diffInMilliseconds($target, false) > 0) {
            return $target->year + 544;
        }

        return $target->year + 543;
    }

    /**
     * Estimate the Buddhist Era (BE) year from a Gregorian date.
     *
     * This method is used internally to resolve recursive issues during Khmer date calculation.
     * It provides an approximate BE year for determining the number of days in a Khmer year,
     * especially around the Khmer New Year, which typically falls in either March or April.
     *
     * @param  CarbonImmutable  $date  The Gregorian date to evaluate
     * @return int Estimated BE year
     */
    public function getMaybeBEYear(CarbonImmutable $date): int
    {
        $solarMonths = (new Constant)->getSolarMonths();

        // If the date is before or around April (month 4), it's considered part of the current BE year.
        if ($date->month <= $solarMonths['មេសា'] + 1) {
            return $date->year + 543;
        }

        // Otherwise, the BE year is incremented
        return $date->year + 544;
    }

    /**
     * Convert Gregorian year to Moha Sakaraj year.
     *
     * Moha Sakaraj (មហាសករាជ) is a traditional calendar system used in Southeast Asia.
     * This function converts a Gregorian year to its corresponding Moha Sakaraj year.
     *
     * Formula: Moha Sakaraj = AD Year - 77
     *
     * @param  int  $adYear  Gregorian year (Anno Domini)
     * @return int Moha Sakaraj year
     */
    public function getMohaSakarajYear(int $adYear): int
    {
        return $adYear - 77;
    }

    /**
     * Calculate Jolak Sakaraj (ចុល្លសករាជ) year based on Khmer New Year cutoff.
     *
     * Jolak Sakaraj is another traditional Southeast Asian era. Its value depends on whether
     * the given date is before or after Khmer New Year.
     *
     * @return int Jolak Sakaraj year
     */
    public function getJolakSakarajYear(CarbonImmutable $date): int
    {
        $gregorianYear = $date->year;
        $newYearMoment = $this->getKhmerNewYearDateTime($gregorianYear);

        if (! $newYearMoment instanceof CarbonImmutable) {
            throw new RuntimeException('Invalid new year moment returned.');
        }

        return $newYearMoment->diffInMilliseconds($date, false) < 0
            ? $gregorianYear + 543 - 1182
            : $gregorianYear + 544 - 1182;
    }

    /**
     * Get Khmer lunar day representation (e.g. ១ កើត, ១៤ រោច).
     *
     * This function splits the lunar day into two halves:
     *   - កើត (waxing moon) for day 1–15
     *   - រោច (waning moon) for day 16–30
     *
     * @param  int  $day  Day number in the lunar cycle (1–30)
     */
    public function getKhmerLunarDay(int $day): LunarDay
    {
        $moonStatuses = (new Constant)->getMoonStatuses();

        return new LunarDay(
            ($day % 15) + 1,
            $day > 14 ? $moonStatuses['រោច'] : $moonStatuses['កើត']
        );
    }

    /**
     * Get the animal year index from the given date based on Khmer zodiac cycle.
     *
     * The Khmer zodiac is a 12-year cycle, and each year corresponds to a specific animal.
     * This method determines the animal index based on whether the date occurs before
     * or after the Khmer New Year (which usually falls in April).
     *
     * @param  CarbonImmutable  $date  The target date
     * @return int The animal year index (0–11), where 0 = Rat, 1 = Ox, ..., 11 = Pig
     */
    public function getAnimalYear(CarbonImmutable $date): int
    {
        $gregorianYear = $date->year;
        $newYearDateTime = $this->getKhmerNewYearDateTime($gregorianYear);

        $beYear = $newYearDateTime->diffInMilliseconds($date, false) < 0
            ? $gregorianYear + 543
            : $gregorianYear + 544;

        return ($beYear + 4) % 12;
    }

    /**
     * Generate the Khmer Lunar Date representation from a given Gregorian date.
     *
     * This method calculates the lunar day (១កើត, ១រោច, etc.), Khmer month,
     * day of the week, animal year, and era year based on traditional Khmer calendar rules.
     * It safely maps internal indexes to Khmer values and ensures strict typing
     * compatibility for PHPStan level max.
     *
     * @param  CarbonImmutable  $target  The input date in the Gregorian calendar.
     * @return KhmerLunarDate The fully computed Khmer lunar date object.
     *
     * @throws InvalidKhmerMonthException
     * @throws VisakhabocheaException
     */
    public function khmerLunarDate(CarbonImmutable $target): KhmerLunarDate
    {
        $constant = new Constant;

        $current = $target->copy();
        $lunar = $this->findLunarDate($current);

        // $dayOfWeekIndex = $constant->dayOfWeeks[$target->format('l')] ?? null;
        $dayOfWeekIndex = $target->dayOfWeek;
        $dayOfWeekName = array_search($dayOfWeekIndex, $constant->dayOfWeeks, true);
        if ($dayOfWeekName === false) {
            throw new RuntimeException("Invalid day of week index: $dayOfWeekIndex");
        }
        $moonDay = $this->getKhmerLunarDay($lunar->getDay());
        $beYear = $this->getBEYear($target);
        $animalYearIndex = $this->getAnimalYear($target);
        $eraYearIndex = $this->getJolakSakarajYear($target) % 10;

        $lunarMonthKey = array_search($moonDay->getMoonStatus(), $constant->moonStatuses, true);
        $animalYearKey = array_search($animalYearIndex, $constant->animalYears, true);
        $eraYearKey = array_search($eraYearIndex, $constant->eraYears, true);
        $khmerMonthKey = array_search($lunar->getMonth(), $constant->lunarMonths, true);

        if (
            $dayOfWeekIndex === null ||
            $lunarMonthKey === false ||
            $khmerMonthKey === false ||
            $animalYearKey === false ||
            $eraYearKey === false
        ) {
            throw new RuntimeException('Invalid Khmer lunar date components.');
        }

        $lunarDay = $this->convertToKhmerNumber($moonDay->getMoonCount()).' '.$lunarMonthKey;

        return new KhmerLunarDate(
            $dayOfWeekName,
            $lunarDay,
            $khmerMonthKey,
            $animalYearKey,
            $eraYearKey,
            $this->convertToKhmerNumber($beYear)
        );
    }

    /**
     * Get the next Khmer lunar month index based on the current month and BE year.
     *
     * @param  int  $khmerMonth  The current Khmer month index
     * @param  int  $BEYear  The Buddhist Era year
     * @return int The next month index
     *
     * @throws InvalidKhmerMonthException
     */
    public function nextMonthOf(int $khmerMonth, int $BEYear): int
    {
        $lunarMonths = (new Constant)->getLunarMonths();

        // Store month keys for readability
        $MK = $lunarMonths;

        return match ($khmerMonth) {
            $MK['មិគសិរ'] => $MK['បុស្ស'],
            $MK['បុស្ស'] => $MK['មាឃ'],
            $MK['មាឃ'] => $MK['ផល្គុន'],
            $MK['ផល្គុន'] => $MK['ចេត្រ'],
            $MK['ចេត្រ'] => $MK['ពិសាខ'],
            $MK['ពិសាខ'] => $MK['ជេស្ឋ'],
            $MK['ជេស្ឋ'] => $this->isKhmerLeapMonth($BEYear)
                ? $MK['បឋមាសាឍ']
                : $MK['អាសាឍ'],
            $MK['បឋមាសាឍ'] => $MK['ទុតិយាសាឍ'],
            $MK['ទុតិយាសាឍ'], $MK['អាសាឍ'] => $MK['ស្រាពណ៍'],
            $MK['ស្រាពណ៍'] => $MK['ភទ្របទ'],
            $MK['ភទ្របទ'] => $MK['អស្សុជ'],
            $MK['អស្សុជ'] => $MK['កត្តិក'],
            $MK['កត្តិក'] => $MK['មិគសិរ'],
            default => throw new InvalidKhmerMonthException("Invalid Khmer month index: $khmerMonth"),
        };
    }

    /**
     * Calculate the Khmer lunar date for a given target date.
     *
     * @param  CarbonImmutable  $target  The Gregorian date to convert.
     * @return LunarDate The corresponding Khmer lunar date.
     *
     * @throws InvalidKhmerMonthException
     */
    public function findLunarDate(CarbonImmutable $target): LunarDate
    {
        $constant = new Constant;
        $lunarMonths = $constant->getLunarMonths();

        $epochDateTime = CarbonImmutable::createFromFormat('!d/m/Y', '1/1/1900', 'Asia/Phnom_Penh');

        $khmerMonth = $lunarMonths['បុស្ស'];

        $cached = $this->cache->remember('chhakitek_lunar_date_'.$target->format('Y-m-d'), 60 * 60 * 24 * 365, function () use ($target, $epochDateTime, $khmerMonth) {
            // Move epoch close to the target year
            if ($target->greaterThan($epochDateTime)) {
                while (true) {
                    $nextEpochYear = $epochDateTime->addYear();
                    $nextBEYear = $this->getMaybeBEYear($nextEpochYear);
                    $daysInYear = $this->getNumberOfDayInKhmerYear($nextBEYear);

                    if ($target->diffInDays($epochDateTime, false) <= $daysInYear) {
                        break;
                    }

                    $epochDateTime = $epochDateTime->addDays($daysInYear);
                }
            } else {
                while (true) {
                    $daysInYear = $this->getNumberOfDayInKhmerYear($this->getMaybeBEYear($epochDateTime));
                    $newEpoch = $epochDateTime->subDays($daysInYear);
                    if ($target->greaterThanOrEqualTo($newEpoch)) {
                        break;
                    }
                    $epochDateTime = $newEpoch;
                }
            }

            // Calculate how many days between target and epoch
            $daysBetween = $epochDateTime->diffInDays($target, false);

            // Advance through months
            while (true) {
                $daysInMonth = $this->getNumberOfDayInKhmerMonth($khmerMonth, $this->getMaybeBEYear($epochDateTime));

                if ($daysBetween < $daysInMonth) {
                    break;
                }

                $epochDateTime = $epochDateTime->addDays($daysInMonth);
                $khmerMonth = $this->nextMonthOf($khmerMonth, $this->getMaybeBEYear($epochDateTime));
                $daysBetween -= $daysInMonth;
            }

            // Store only scalar primitives so deserialization never produces
            // __PHP_Incomplete_Class when the autoloader has not yet run.
            return ['day' => (int) $daysBetween, 'month' => $khmerMonth];
        });

        return new LunarDate($cached['day'], $cached['month'], $target);
    }

    /**
     * Get the Khmer New Year date and time based on traditional solar and lunar data.
     *
     * The New Year falls on the 3rd or 4th day after the Sotin day depending on the Angsar.
     * This calculation aligns the Soriyatra lunar calendar with the Gregorian calendar.
     *
     * @param  int  $gregorianYear  The Gregorian year.
     * @return CarbonImmutable The calculated Khmer New Year date and time.
     *
     * @throws InvalidKhmerMonthException
     * @throws TimeOfNewYearException
     */
    public function getKhmerNewYearDateTime(int $gregorianYear): CarbonImmutable
    {
        $jsYear = ($gregorianYear + 544) - 1182;
        $info = new SoriyatraLerngSak($jsYear);

        $newYearsDaySotins = $info->getNewYearDaySotins();
        $numberNewYearDay = $newYearsDaySotins[0]->getAngsar() === 0 ? 4 : 3;

        $timeOfNewYear = $info->getTimeOfNewYear();
        $minutes = sprintf('%02d', $timeOfNewYear->getMinute());
        $hour = sprintf('%02d', $timeOfNewYear->getHour());

        // The Soriyatra time is already Phnom Penh local time, so it has to be parsed
        // as such. Parsing it in the process default timezone and converting afterwards
        // would shift the clock and, for a late enough hour, roll the epoch to 18 April.
        $epochLerngSak = CarbonImmutable::createFromFormat(
            'Y-m-d H:i',
            "$gregorianYear-04-17 $hour:$minutes",
            'Asia/Phnom_Penh'
        );

        $lunarDate = $this->findLunarDate($epochLerngSak);
        $lunarDay = $lunarDate->getDay();
        $lunarMonth = $lunarDate->getMonth();

        $lunarLerngSak = $info->getLunarDateLerngSak();
        $lunarLerngSakDay = $lunarLerngSak->getDay();
        $lunarLerngSakMonth = $lunarLerngSak->getMonth();

        // Count the real days between the Lerng Sak lunar date and the lunar date of
        // the 17 April epoch. Lunar months are 29 or 30 days long, so assuming a fixed
        // 30 shifts the result by a day whenever the two fall in different months.
        $beYear = $this->getMaybeBEYear($epochLerngSak);

        $diffFromEpoch = $this->diffInLunarDays(
            $lunarLerngSakMonth,
            $lunarLerngSakDay,
            $lunarMonth,
            $lunarDay,
            $beYear
        );

        return $epochLerngSak->subDays($diffFromEpoch + $numberNewYearDay - 1);
    }

    /**
     * Count the days from one lunar date to another within the same Khmer year.
     *
     * Month lengths alternate between 29 and 30 days, so the distance is measured by
     * walking the real months rather than assuming a fixed length. The two dates are
     * expected to be at most a couple of months apart; the result is negative when the
     * second date precedes the first.
     *
     * @param  int  $fromMonth  Khmer month index of the first date
     * @param  int  $fromDay  Zero-based day within $fromMonth
     * @param  int  $toMonth  Khmer month index of the second date
     * @param  int  $toDay  Zero-based day within $toMonth
     * @param  int  $beYear  Buddhist Era year both dates belong to
     *
     * @throws InvalidKhmerMonthException When the months are too far apart to relate.
     */
    private function diffInLunarDays(int $fromMonth, int $fromDay, int $toMonth, int $toDay, int $beYear): int
    {
        $forward = $this->daysBetweenLunarMonths($fromMonth, $toMonth, $beYear);

        if ($forward !== null) {
            return $forward + $toDay - $fromDay;
        }

        $backward = $this->daysBetweenLunarMonths($toMonth, $fromMonth, $beYear);

        if ($backward !== null) {
            return -($backward + $fromDay - $toDay);
        }

        throw new InvalidKhmerMonthException(
            "Khmer months $fromMonth and $toMonth are not adjacent in BE year $beYear."
        );
    }

    /**
     * Total days spanned by walking forward from one Khmer month to another.
     *
     * Returns null when $toMonth is not reached within the search limit, which lets the
     * caller retry in the opposite direction instead of wrapping around a whole year.
     *
     * @throws InvalidKhmerMonthException
     */
    private function daysBetweenLunarMonths(int $fromMonth, int $toMonth, int $beYear): ?int
    {
        $days = 0;
        $month = $fromMonth;

        // Both dates sit within days of 17 April, so they never span more than two months.
        for ($step = 0; $step < 3; $step++) {
            if ($month === $toMonth) {
                return $days;
            }

            $days += $this->getNumberOfDayInKhmerMonth($month, $beYear);
            $month = $this->nextMonthOf($month, $beYear);
        }

        return null;
    }

    /**
     * Use Laravel's persistent cache when running inside a bound application,
     * otherwise fall back to the framework-free in-memory cache.
     */
    private function resolveDefaultCache(): CacheRepository
    {
        try {
            if (class_exists(Cache::class) && Cache::getFacadeApplication() !== null) {
                return new LaravelCache;
            }
        } catch (Throwable) {
            // Laravel is unavailable; fall through to the in-memory cache.
        }

        return new ArrayCache;
    }
}
