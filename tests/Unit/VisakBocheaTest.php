<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Traits\HasChhankitek;
use Carbon\CarbonImmutable;

uses(HasChhankitek::class);

it('resolves a date that is Visak Bochea', function () {
    $toLunarDate = $this->chhankitek(CarbonImmutable::parse('2025-05-11')->setTimezone('Asia/Phnom_Penh'));

    expect($toLunarDate->getDayOfWeek())->toBe('អាទិត្យ')
        ->and($toLunarDate->getLunarDay())->toBe('១៥ កើត')
        ->and($toLunarDate->getLunarMonth())->toBe('ពិសាខ')
        ->and($toLunarDate->getLunarZodiac())->toBe('ម្សាញ់')
        ->and($toLunarDate->getLunarEra())->toBe('សប្តស័ក')
        ->and($toLunarDate->getLunarYear())->toBe('២៥៦៨');
});

it('rolls over to the next lunar year on the following day', function () {
    $toLunarDate = $this->chhankitek(CarbonImmutable::parse('2025-05-12')->setTimezone('Asia/Phnom_Penh'));

    expect($toLunarDate->getLunarYear())->toBe('២៥៦៩');
});
