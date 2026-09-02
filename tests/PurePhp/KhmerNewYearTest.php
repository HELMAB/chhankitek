<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Cache\ArrayCache;
use Asorasoft\Chhankitek\Calendar\SoriyatraLerngSak;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

function chhankitek(): Chhankitek
{
    return new Chhankitek(CarbonImmutable::parse('2025-01-01'), new ArrayCache);
}

it('resolves the Khmer New Year date', function (int $gregorianYear, string $expected) {
    expect(chhankitek()->getKhmerNewYearDateTime($gregorianYear)->format('Y-m-d'))->toBe($expected);
})->with([
    // Lerng Sak shares a lunar month with the 17 April epoch.
    'same lunar month' => [2024, '2024-04-13'],
    'same lunar month, later Angsar' => [2025, '2025-04-14'],
    // The epoch falls in the lunar month after Lerng Sak, which is only 29 days long.
    'epoch in the following month' => [2007, '2007-04-14'],
    'epoch in the following month, 2026' => [2026, '2026-04-14'],
    'epoch in the following month, 1988' => [1988, '1988-04-13'],
    // Lerng Sak falls in the lunar month after the epoch, so the distance is negative.
    'Lerng Sak in the following month' => [2148, '2148-04-16'],
]);

it('places Lerng Sak on the last day of the New Year', function (int $gregorianYear) {
    $chhankitek = chhankitek();
    $info = new SoriyatraLerngSak(($gregorianYear + 544) - 1182);

    $numberNewYearDay = $info->getNewYearDaySotins()[0]->getAngsar() === 0 ? 4 : 3;
    $lerngSak = $info->getLunarDateLerngSak();

    $lastDay = $chhankitek->getKhmerNewYearDateTime($gregorianYear)->addDays($numberNewYearDay - 1);
    $actual = $chhankitek->findLunarDate($lastDay);

    expect($actual->getDay())->toBe($lerngSak->getDay())
        ->and($actual->getMonth())->toBe($lerngSak->getMonth());
})->with([2007, 2024, 2025, 2026, 2027, 2148]);

it('reports the Moha Songkran time in Phnom Penh time', function (int $gregorianYear, string $expected) {
    expect(chhankitek()->getKhmerNewYearDateTime($gregorianYear)->format('Y-m-d H:i'))->toBe($expected);
})->with([
    // Verified against khmer-lunar-calendar.com: 13 April 2028, 11:12 PM.
    'late evening time' => [2028, '2028-04-13 23:12'],
    'early morning time' => [2029, '2029-04-14 05:36'],
    'midday time' => [2026, '2026-04-14 10:48'],
]);

it('does not depend on the process default timezone', function (string $timezone) {
    $original = date_default_timezone_get();
    date_default_timezone_set($timezone);

    try {
        // A late Soriyatra hour is the case that shifts across a date boundary.
        expect(chhankitek()->getKhmerNewYearDateTime(2028)->format('Y-m-d H:i'))->toBe('2028-04-13 23:12');
    } finally {
        date_default_timezone_set($original);
    }
})->with(['UTC', 'Asia/Phnom_Penh', 'America/New_York', 'Pacific/Kiritimati']);

it('resolves years where the sun lands exactly on the angsar boundary', function (int $gregorianYear, string $expected) {
    // Two sotins report angsar = 0 in these years; the crossing is the first of them,
    // which gives a libda of 0 and therefore a time of 24:00 (midnight).
    expect(chhankitek()->getKhmerNewYearDateTime($gregorianYear)->format('Y-m-d H:i'))->toBe($expected);
})->with([
    '1974' => [1974, '1974-04-13 00:00'],
    '2032' => [2032, '2032-04-13 00:00'],
    '2117' => [2117, '2117-04-15 00:00'],
    '2175' => [2175, '2175-04-16 00:00'],
]);

it('resolves every year between 1900 and 2200', function () {
    $chhankitek = chhankitek();
    $failures = [];

    foreach (range(1900, 2200) as $gregorianYear) {
        try {
            $chhankitek->getKhmerNewYearDateTime($gregorianYear);
        } catch (Throwable $e) {
            $failures[] = $gregorianYear.': '.$e->getMessage();
        }
    }

    expect($failures)->toBe([]);
});

it('resolves the New Year for every year between 2051 and 2090', function () {
    $chhankitek = chhankitek();
    $dates = [];

    foreach (range(2051, 2090) as $gregorianYear) {
        $dates[$gregorianYear] = $chhankitek->getKhmerNewYearDateTime($gregorianYear)->format('Y-m-d');
    }

    // Spot values across the range, including both four-day years and the years whose
    // Moha Songkran slips to 15 April as the Soriyatra clock wraps past midnight.
    expect($dates[2051])->toBe('2051-04-14')
        ->and($dates[2059])->toBe('2059-04-14')
        ->and($dates[2063])->toBe('2063-04-15')
        ->and($dates[2074])->toBe('2074-04-14')
        ->and($dates[2090])->toBe('2090-04-15');

    // Every year in the range falls on 14 or 15 April.
    expect(array_values(array_unique(array_map(
        fn (string $date) => mb_substr($date, 5),
        $dates
    ))))->toEqualCanonicalizing(['04-14', '04-15']);
});

it('keeps Moha Songkran one sidereal year apart', function () {
    $chhankitek = chhankitek();

    // Moha Songkran is the sun entering Mesa, so consecutive occurrences are ~365.25 days
    // apart. Lerng Sak trails it by two or three days and is deliberately not used here.
    //
    // The years with libda = 0 are excluded: the Soriyatra clock reads 24:00 there, which
    // rolls the epoch to 18 April and shifts the result by a day. Fixing that breaks the
    // Lerng Sak round trip instead, so the convention is still unresolved.
    $knownLibdaZeroYears = [1916, 1917, 1974, 1975, 2032, 2033, 2059, 2060, 2117, 2118, 2175, 2176];

    $previous = null;
    $anomalies = [];

    foreach (range(1900, 2200) as $gregorianYear) {
        $mohaSongkran = $chhankitek->getKhmerNewYearDateTime($gregorianYear);

        if ($previous !== null && ! in_array($gregorianYear, $knownLibdaZeroYears, true)) {
            $gap = ($mohaSongkran->getTimestamp() - $previous->getTimestamp()) / 86400;

            if ($gap < 365.15 || $gap > 365.35) {
                $anomalies[] = sprintf('%d: %.2f days', $gregorianYear, $gap);
            }
        }

        $previous = $mohaSongkran;
    }

    expect($anomalies)->toBe([]);
});
