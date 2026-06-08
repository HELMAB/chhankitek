<?php

declare(strict_types=1);

it('trims surrounding characters', function (string $value, string $expected) {
    expect(mbTrimNumber($value))->toBe($expected);
})->with([
    'plain whitespace' => ['  ១២៣  ', '១២៣'],
    'tabs and newlines' => ["\t\n១២៣\n\t", '១២៣'],
    'leading zero-width space' => ["\u{200B}១២៣", '១២៣'],
    'trailing zero-width space' => ["១២៣\u{200B}", '១២៣'],
    'surrounding byte order mark' => ["\u{FEFF}១២៣\u{FEFF}", '១២៣'],
    'mixed leading and trailing' => [" \u{200B}\u{FEFF}\t១២៣\u{FEFF} ", '១២៣'],
    'already trimmed' => ['១២៣', '១២៣'],
    'empty string' => ['', ''],
    'only trimmable characters' => [" \u{200B}\u{FEFF}", ''],
]);

it('preserves characters inside the value', function () {
    expect(mbTrimNumber("\u{200B}១២ ៣៤\u{FEFF}"))->toBe('១២ ៣៤');
});
