<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Cache\CacheRepository;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

if (! function_exists('toLunarDate')) {
    function toLunarDate(CarbonImmutable $target, ?CacheRepository $cache = null)
    {
        return (new Chhankitek($target, $cache))->formatKhmerDate;
    }
}

if (! function_exists('mbTrimNumber')) {
    function mbTrimNumber(string $value): string
    {
        return preg_replace(
            '/^[\s\x{200B}\x{FEFF}]+|[\s\x{200B}\x{FEFF}]+$/u',
            '',
            $value
        );
    }
}
