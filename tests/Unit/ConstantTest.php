<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Tests\Unit;

use Asorasoft\Chhankitek\Calendar\Constant;
use Asorasoft\Chhankitek\Tests\TestCase;

class ConstantTest extends TestCase
{
    /**
     * Test animal years array contains correct values.
     *
     * @return void
     */
    public function testAnimalYearsContainCorrectValues(): void
    {
        $constant = new Constant();
        $animalYears = $constant->getAnimalYears();
        
        // Verify the expected animal years are present
        $expectedAnimals = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ'];
        
        foreach ($expectedAnimals as $index => $animal) {
            $this->assertArrayHasKey($animal, $animalYears, "Animal '{$animal}' should be present in animal years");
            $this->assertEquals($index, $animalYears[$animal], "Animal '{$animal}' should have index {$index}");
        }
        
        // Verify that the corrected animal (មមី) is at index 6
        $this->assertArrayHasKey('មមី', $animalYears, 'Corrected animal name মমী should be present');
        $this->assertEquals(6, $animalYears['មមី'], 'Animal ममी should be at index 6');
        
        // Verify that the incorrect animal (ממីរ) is NOT present
        $this->assertArrayNotHasKey('មមីរ', $animalYears, 'Incorrect animal name ममীর should not be present');
    }
}