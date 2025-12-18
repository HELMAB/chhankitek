# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Chhankitek** is a Laravel package that converts Gregorian dates (AD) to Khmer Lunar (Chhankitek) calendar format. The package implements traditional Khmer astronomical algorithms to calculate lunar dates, including leap months, leap days, zodiac animals, and Buddhist Era years.

### Key Concepts

- **Buddhist Era (BE)**: The primary calendar era used, calculated as AD + 543/544 (depends on Visakha Bochea day)
- **Lunar Calendar**: 12-month cycle with occasional 13th month (leap month/Adhikameas)
- **Leap Calculations**:
  - Leap month years have 384 days (13 months)
  - Leap day years have 355 days (extra day in month of ជេស្ឋ)
  - Regular years have 354 days
- **Visakha Bochea**: Buddhist holy day that marks the BE year transition (14th waxing moon of ពិសាខ month)
- **Khmer New Year**: Falls 3-4 days after Sotin day, typically in mid-April

## Testing

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test-coverage
```

Tests are organized in `tests/Unit/` and use PHPUnit with Orchestra Testbench for Laravel package testing.

## Architecture

### Core Components

**Main Conversion Class**: `src/Chhankitek.php`
- Entry point for all date conversions
- Handles caching (1 year TTL using Laravel's cache system)
- Contains all astronomical calculation methods (Aharkun, Bodithey, Avoman, etc.)
- Key methods:
  - `khmerLunarDate()`: Main conversion method that returns `KhmerLunarDate` object
  - `findLunarDate()`: Calculates lunar date from Gregorian date using epoch (1/1/1900)
  - `getBEYear()`: Determines Buddhist Era year based on Visakha Bochea
  - `getKhmerNewYearDateTime()`: Calculates exact Khmer New Year moment

**Public API**:
- `HasChhankitek` trait: Provides `chhankiteck()` method for use in Laravel controllers
- `toLunarDate()` helper function: Global helper for quick conversions
- Both return `KhmerLunarDate` object with methods: `getDayOfWeek()`, `getLunarDay()`, `getLunarMonth()`, `getLunarZodiac()`, `getLunarEra()`, `getLunarYear()`, `toString()`

**Calendar Support Classes** (`src/Calendar/`):
- `Constant.php`: Defines all Khmer calendar constants (months, zodiac animals, era years, days of week)
- `KhmerLunarDate.php`: Value object representing a complete Khmer lunar date
- `LunarDate.php`, `LunarDay.php`: Internal date representation classes
- `SoriyatraLerngSak.php`: Calculates solar year alignment and New Year timing
- `TimeOfNewYear.php`, `KhmerNewYear.php`: New Year calculation helpers
- Other specialized calculation classes (Phol, SunInfo, etc.)

**Exceptions** (`src/Exception/`):
- `InvalidKhmerMonthException`: Thrown when month index is invalid
- `VisakhabocheaException`: Thrown when Visakha Bochea day cannot be found
- `TimeOfNewYearException`: Thrown when New Year calculation fails

### Algorithm Flow

1. **Input**: CarbonImmutable date (must be in 'Asia/Phnom_Penh' timezone)
2. **Epoch Movement**: Starts from 1/1/1900 and moves toward target date by adding/subtracting Khmer lunar years
3. **Month Iteration**: Once close to target year, iterates through lunar months considering leap months
4. **Day Calculation**: Determines exact lunar day within the month
5. **Component Assembly**: Calculates BE year, animal year, era year, and assembles final KhmerLunarDate object
6. **Caching**: Result cached for 365 days to avoid recalculation

### Leap Year Logic

The package implements complex leap year rules:
- `getBoditheyLeap()`: Determines if year has leap month/day based on Bodithey (25 ≤ bodithey ≤ 5) and Avoman values
- `getProtetinLeap()`: Resolves conflicts when both leap month and leap day occur (prioritizes leap month, defers leap day to next year)
- Special cases handle consecutive years with Bodithey values 25/5 and 24/6

### Number Conversion

`HasKhmerNumberConversion` trait (in `src/Traits/`) converts Arabic numerals to Khmer numerals (០-៩) for display in the traditional format.

## Important Implementation Details

- All dates must use `CarbonImmutable` (not `Carbon`) with 'Asia/Phnom_Penh' timezone
- Date format internally uses 'd/m/Y' format before conversion
- Caching is essential for performance: each conversion result is cached for 1 year
- The epoch date (1/1/1900) is hardcoded as the calculation starting point
- Lunar months use numeric indexes defined in `Constant::$lunarMonths` array
- The package supports Laravel 10, 11, and 12 with PHP 8.2+

## Development Notes

- Package uses strict types (`declare(strict_types=1)`) throughout
- Follow PSR-4 autoloading: `Asorasoft\Chhankitek` namespace
- Service provider is minimal (no config publishing or migrations needed)
- Helper function defined in `src/helpers.php` (autoloaded via composer.json)
- When adding new methods to `Chhankitek.php`, consider if caching is needed for performance
