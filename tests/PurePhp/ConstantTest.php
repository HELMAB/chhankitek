<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Calendar\Constant;

it('contains the correct animal year values', function () {
    $animalYears = (new Constant)->getAnimalYears();

    $expectedAnimals = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ'];

    foreach ($expectedAnimals as $index => $animal) {
        expect($animalYears)->toHaveKey($animal, $index);
    }

    expect($animalYears)
        ->toHaveKey('មមី', 6)
        ->not->toHaveKey('មមីរ');
});
