<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Calendar\Constant;

it('contains the correct animal years', function () {
    $animalYears = (new Constant)->getAnimalYears();

    $expectedAnimals = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ'];

    foreach ($expectedAnimals as $index => $animal) {
        expect($animalYears)
            ->toHaveKey($animal)
            ->and($animalYears[$animal])->toBe($index);
    }

    expect($animalYears)
        ->toHaveKey('មមី')
        ->and($animalYears['មមី'])->toBe(6)
        ->and($animalYears)->not->toHaveKey('មមីរ');
});
