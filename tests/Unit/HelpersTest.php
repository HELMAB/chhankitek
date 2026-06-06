<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Tests\Unit;

use Asorasoft\Chhankitek\Tests\TestCase;

final class HelpersTest extends TestCase
{
    /**
     * Test that a plain value without surrounding whitespace is returned unchanged.
     */
    public function test_returns_value_unchanged_when_no_whitespace(): void
    {
        $this->assertSame('០១២', mbTrimNumber('០១២'));
    }

    /**
     * Test that regular leading and trailing whitespace is stripped.
     */
    public function test_strips_regular_whitespace_from_both_ends(): void
    {
        $this->assertSame('០១២', mbTrimNumber("  \t០១២\n "));
    }

    /**
     * Test that a leading zero-width space (U+200B) is stripped.
     */
    public function test_strips_zero_width_space(): void
    {
        $this->assertSame('០១២', mbTrimNumber("\u{200B}០១២\u{200B}"));
    }

    /**
     * Test that a zero-width no-break space / BOM (U+FEFF) is stripped.
     */
    public function test_strips_bom(): void
    {
        $this->assertSame('០១២', mbTrimNumber("\u{FEFF}០១២\u{FEFF}"));
    }

    /**
     * Test that a mix of regular and zero-width whitespace is stripped from both ends.
     */
    public function test_strips_mixed_whitespace_and_zero_width_characters(): void
    {
        $this->assertSame('០១២', mbTrimNumber("\u{FEFF} \u{200B}០១២\u{200B} \u{FEFF}"));
    }

    /**
     * Test that whitespace inside the value is preserved.
     */
    public function test_preserves_internal_whitespace(): void
    {
        $this->assertSame('០១ ២', mbTrimNumber(' ០១ ២ '));
    }

    /**
     * Test that an empty string is returned as an empty string.
     */
    public function test_returns_empty_string_for_empty_input(): void
    {
        $this->assertSame('', mbTrimNumber(''));
    }

    /**
     * Test that a whitespace-only value is reduced to an empty string.
     */
    public function test_returns_empty_string_for_whitespace_only_input(): void
    {
        $this->assertSame('', mbTrimNumber("\u{200B}  \u{FEFF}\n"));
    }
}
