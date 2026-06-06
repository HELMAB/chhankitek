<?php

declare(strict_types=1);

it('trims surrounding whitespace and zero-width characters', function (string $input, string $expected) {
    expect(mbTrimNumber($input))->toBe($expected);
})->with([
    'no whitespace' => ['០១២', '០១២'],
    'regular whitespace' => ["  \t០១២\n ", '០១២'],
    'zero-width space (U+200B)' => ["\u{200B}០១២\u{200B}", '០១២'],
    'BOM (U+FEFF)' => ["\u{FEFF}០១២\u{FEFF}", '០១២'],
    'mixed whitespace and zero-width' => ["\u{FEFF} \u{200B}០១២\u{200B} \u{FEFF}", '០១២'],
    'empty string' => ['', ''],
    'whitespace only' => ["\u{200B}  \u{FEFF}\n", ''],
]);

it('preserves whitespace inside the value', function () {
    expect(mbTrimNumber(' ០១ ២ '))->toBe('០១ ២');
});
