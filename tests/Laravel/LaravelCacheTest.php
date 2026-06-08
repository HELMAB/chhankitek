<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Cache\LaravelCache;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

describe('LaravelCache', function () {
    it('delegates to the underlying Laravel cache store', function () {
        $cache = new LaravelCache;

        $value = $cache->remember('laravel_key', 60, fn () => 'value');

        expect($value)->toBe('value')
            ->and(Cache::get('laravel_key'))->toBe('value');
    });

    it('returns the stored value without rerunning the callback', function () {
        $cache = new LaravelCache;
        Cache::put('existing', 'stored', 60);

        $value = $cache->remember('existing', 60, fn () => 'fresh');

        expect($value)->toBe('stored');
    });
});

describe('Chhankitek default cache resolution', function () {
    it('uses Laravel cache by default when an application is bound', function () {
        $chhankitek = new Chhankitek(CarbonImmutable::parse('2025-05-11'));

        $resolved = (new ReflectionProperty($chhankitek, 'cache'))->getValue($chhankitek);

        expect($resolved)->toBeInstanceOf(LaravelCache::class);
    });

    it('persists the resolved lunar date through the Laravel cache', function () {
        Cache::flush();

        new Chhankitek(CarbonImmutable::parse('2025-05-11'));

        expect(Cache::has('chhakitek_lunar_date_2025-05-11'))->toBeTrue();
    });
});
