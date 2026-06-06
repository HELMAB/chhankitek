<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Tests\Unit;

use Asorasoft\Chhankitek\Tests\TestCase;
use Asorasoft\Chhankitek\Traits\HasKhmerNumberConversion;

final class HasKhmerNumberConversionTest extends TestCase
{
    use HasKhmerNumberConversion;

    /**
     * Test that an integer is converted to its Khmer numeral representation.
     */
    public function test_converts_integer_to_khmer_number(): void
    {
        $this->assertSame('១២៣៤៥៦៧៨៩០', $this->convertToKhmerNumber(1234567890));
    }

    /**
     * Test that a numeric string is converted to its Khmer numeral representation.
     */
    public function test_converts_numeric_string_to_khmer_number(): void
    {
        $this->assertSame('២០២៦', $this->convertToKhmerNumber('2026'));
    }

    /**
     * Test that zero is converted to the Khmer zero numeral.
     */
    public function test_converts_zero(): void
    {
        $this->assertSame('០', $this->convertToKhmerNumber(0));
    }

    /**
     * Test that a single digit is converted correctly.
     */
    public function test_converts_single_digit(): void
    {
        $this->assertSame('៥', $this->convertToKhmerNumber(5));
    }

    /**
     * Test that surrounding whitespace and zero-width characters are trimmed from the result.
     */
    public function test_trims_surrounding_whitespace_from_result(): void
    {
        $this->assertSame('១២', $this->convertToKhmerNumber("\u{200B} 12 \u{FEFF}"));
    }
}
