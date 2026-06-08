<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Traits\HasChhankitek;
use Carbon\CarbonImmutable;

uses(HasChhankitek::class);

<<<<<<< HEAD
it('resolves a date that is Visak Bochea', function () {
=======
it('resolves the lunar date for Visak Bochea', function () {
>>>>>>> 97a00310c5ae7b316cfb052e6891ed353e6c7282
    $toLunarDate = $this->chhankitek(CarbonImmutable::parse('2025-05-11')->setTimezone('Asia/Phnom_Penh'));

    expect($toLunarDate->getDayOfWeek())->toBe('អាទិត្យ')
        ->and($toLunarDate->getLunarDay())->toBe('១៥ កើត')
        ->and($toLunarDate->getLunarMonth())->toBe('ពិសាខ')
        ->and($toLunarDate->getLunarZodiac())->toBe('ម្សាញ់')
        ->and($toLunarDate->getLunarEra())->toBe('សប្តស័ក')
        ->and($toLunarDate->getLunarYear())->toBe('២៥៦៨');
});

<<<<<<< HEAD
it('rolls over to the next lunar year on the following day', function () {
=======
it('advances the lunar year on the following day', function () {
>>>>>>> 97a00310c5ae7b316cfb052e6891ed353e6c7282
    $toLunarDate = $this->chhankitek(CarbonImmutable::parse('2025-05-12')->setTimezone('Asia/Phnom_Penh'));

    expect($toLunarDate->getLunarYear())->toBe('២៥៦៩');
});
