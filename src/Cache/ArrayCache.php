<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Cache;

/**
 * Framework-free, in-memory cache.
 *
 * Values live for the lifetime of the instance, so the TTL is ignored. This is
 * the default for pure PHP usage and still de-duplicates repeated lookups
 * within a single request or script run.
 */
final class ArrayCache implements CacheRepository
{
    /** @var array<string, mixed> */
    private array $store = [];

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        if (! array_key_exists($key, $this->store)) {
            $this->store[$key] = $callback();
        }

        return $this->store[$key];
    }
}
