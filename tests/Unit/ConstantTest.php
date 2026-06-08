<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Calendar\Constant;

<<<<<<< HEAD
it('contains the correct animal years', function () {
=======
it('contains the correct animal year values', function () {
>>>>>>> 97a00310c5ae7b316cfb052e6891ed353e6c7282
    $animalYears = (new Constant)->getAnimalYears();

    $expectedAnimals = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ'];

    foreach ($expectedAnimals as $index => $animal) {
<<<<<<< HEAD
        expect($animalYears)
            ->toHaveKey($animal)
            ->and($animalYears[$animal])->toBe($index);
    }

    expect($animalYears)
        ->toHaveKey('មមី')
        ->and($animalYears['មមី'])->toBe(6)
        ->and($animalYears)->not->toHaveKey('មមីរ');
=======
        expect($animalYears)->toHaveKey($animal, $index);
    }

    expect($animalYears)
        ->toHaveKey('មមី', 6)
        ->not->toHaveKey('មមីរ');
>>>>>>> 97a00310c5ae7b316cfb052e6891ed353e6c7282
});
