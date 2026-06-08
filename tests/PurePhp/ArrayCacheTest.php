<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Cache\ArrayCache;
use Asorasoft\Chhankitek\Cache\CacheRepository;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

describe('ArrayCache', function () {
    it('computes and returns the value on a cache miss', function () {
        $cache = new ArrayCache;

        expect($cache->remember('key', 60, fn () => 'value'))->toBe('value');
    });

    it('runs the callback only once per key', function () {
        $cache = new ArrayCache;
        $calls = 0;

        $callback = function () use (&$calls) {
            $calls++;

            return 'computed';
        };

        $cache->remember('key', 60, $callback);
        $cache->remember('key', 60, $callback);

        expect($calls)->toBe(1);
    });

    it('caches values independently per key', function () {
        $cache = new ArrayCache;

        $first = $cache->remember('first', 60, fn () => 'a');
        $second = $cache->remember('second', 60, fn () => 'b');

        expect($first)->toBe('a')
            ->and($second)->toBe('b');
    });

    it('caches falsy values without recomputing', function () {
        $cache = new ArrayCache;
        $calls = 0;

        $callback = function () use (&$calls) {
            $calls++;

            return null;
        };

        expect($cache->remember('key', 60, $callback))->toBeNull()
            ->and($cache->remember('key', 60, $callback))->toBeNull()
            ->and($calls)->toBe(1);
    });
});

describe('Chhankitek with an injected cache', function () {
    it('uses the injected cache instance', function () {
        $cache = new ArrayCache;

        $chhankitek = new Chhankitek(CarbonImmutable::parse('2025-05-11'), $cache);

        $resolved = (new ReflectionProperty($chhankitek, 'cache'))->getValue($chhankitek);

        expect($resolved)->toBe($cache);
    });

    it('populates the injected cache while resolving the lunar date', function () {
        $cache = new ArrayCache;

        new Chhankitek(CarbonImmutable::parse('2025-05-11'), $cache);

        $store = (new ReflectionProperty($cache, 'store'))->getValue($cache);

        expect($store)->toHaveKey('chhakitek_lunar_date_2025-05-11');
    });

    it('reuses the cached lunar date on repeated lookups', function () {
        $spy = new class implements CacheRepository
        {
            public int $calls = 0;

            /** @var array<string, mixed> */
            private array $store = [];

            public function remember(string $key, int $ttl, callable $callback): mixed
            {
                $this->calls++;

                return $this->store[$key] ??= $callback();
            }
        };

        $chhankitek = new Chhankitek(CarbonImmutable::parse('2025-05-11'), $spy);
        $target = CarbonImmutable::parse('2025-05-11')->setTimezone('Asia/Phnom_Penh');

        $first = $chhankitek->findLunarDate($target);
        $second = $chhankitek->findLunarDate($target);

        expect($first)->toEqual($second)
            ->and($spy->calls)->toBeGreaterThan(0);
    });
});
