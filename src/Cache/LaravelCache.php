<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Cache;

use Illuminate\Support\Facades\Cache;

/**
 * Adapter backed by Laravel's cache, giving persistent caching across requests.
 *
 * Requires illuminate/support and a bound cache store; use it inside a Laravel
 * application, otherwise fall back to {@see ArrayCache}.
 */
final class LaravelCache implements CacheRepository
{
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }
}
