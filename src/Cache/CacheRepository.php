<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Cache;

interface CacheRepository
{
    /**
     * Return the cached value for the key, or compute it via the callback and store it.
     *
     * @param  int  $ttl  Time-to-live in seconds.
     */
    public function remember(string $key, int $ttl, callable $callback): mixed;
}
