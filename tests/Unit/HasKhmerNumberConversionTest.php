<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Traits\HasKhmerNumberConversion;

uses(HasKhmerNumberConversion::class);

it('converts an integer to its Khmer numeral representation', function () {
    expect($this->convertToKhmerNumber(1234567890))->toBe('១២៣៤៥៦៧៨៩០');
});

it('converts a numeric string to its Khmer numeral representation', function () {
    expect($this->convertToKhmerNumber('2026'))->toBe('២០២៦');
});

it('converts zero to the Khmer zero numeral', function () {
    expect($this->convertToKhmerNumber(0))->toBe('០');
});

it('converts a single digit', function () {
    expect($this->convertToKhmerNumber(5))->toBe('៥');
});

it('trims surrounding whitespace and zero-width characters from the result', function () {
    expect($this->convertToKhmerNumber("\u{200B} 12 \u{FEFF}"))->toBe('១២');
});
