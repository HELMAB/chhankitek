<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Cache\ArrayCache;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

it('resolves the lunar date for Visak Bochea without Laravel', function () {
    $target = CarbonImmutable::parse('2025-05-11')->setTimezone('Asia/Phnom_Penh');

    $toLunarDate = (new Chhankitek($target, new ArrayCache))->formatKhmerDate;

    expect($toLunarDate->getDayOfWeek())->toBe('អាទិត្យ')
        ->and($toLunarDate->getLunarDay())->toBe('១៥ កើត')
        ->and($toLunarDate->getLunarMonth())->toBe('ពិសាខ')
        ->and($toLunarDate->getLunarZodiac())->toBe('ម្សាញ់')
        ->and($toLunarDate->getLunarEra())->toBe('សប្តស័ក')
        ->and($toLunarDate->getLunarYear())->toBe('២៥៦៨');
});

it('advances the lunar year on the following day without Laravel', function () {
    $target = CarbonImmutable::parse('2025-05-12')->setTimezone('Asia/Phnom_Penh');

    $toLunarDate = (new Chhankitek($target, new ArrayCache))->formatKhmerDate;

    expect($toLunarDate->getLunarYear())->toBe('២៥៦៩');
});
