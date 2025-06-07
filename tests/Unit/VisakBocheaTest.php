<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Tests\Unit;

use Asorasoft\Chhankitek\Traits\HasChhankitek;
use Asorasoft\Chhankitek\Tests\TestCase;
use Carbon\CarbonImmutable;

class VisakBocheaTest extends TestCase
{
    use HasChhankitek;

    /**
     * Test if a specific date is Visak Bochea.
     *
     * @return void
     */
    public function testIsVisakBochea(): void
    {
        // Set the date known to be Visak Bochea in 2025
        $date = '2025-05-11';
        $toLunarDate = $this->chhankiteck(CarbonImmutable::parse($date)->setTimezone('Asia/Phnom_Penh'));

        // Assert day of week
        $this->assertEquals('អាទិត្យ', $toLunarDate->getDayOfWeek(), 'Failed to verify day of week');

        // Assert lunar day
        $this->assertEquals('១៥ កើត', $toLunarDate->getLunarDay(), 'Failed to verify lunar day');

        // Assert lunar month
        $this->assertEquals('ពិសាខ', $toLunarDate->getLunarMonth(), 'Failed to verify lunar month');

        // Assert lunar zodiac
        $this->assertEquals('ម្សាញ់', $toLunarDate->getLunarZodiac(), 'Failed to verify lunar zodiac');

        // Assert lunar era
        $this->assertEquals('សប្តស័ក', $toLunarDate->getLunarEra(), 'Failed to verify lunar era');

        // Assert lunar year
        $this->assertEquals('២៥៦៨', $toLunarDate->getLunarYear(), 'Failed to verify lunar year');

        // Set the date known to be Visak Bochea in 2025
        $date = '2025-05-12';
        $toLunarDate = $this->chhankiteck(CarbonImmutable::parse($date)->setTimezone('Asia/Phnom_Penh'));
        
        // Assert lunar year
        $this->assertEquals('២៥៦៩', $toLunarDate->getLunarYear(), 'Failed to verify lunar year');
    }
}
