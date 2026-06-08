<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Traits;

use Asorasoft\Chhankitek\Cache\CacheRepository;
use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

trait HasChhankitek
{
    public function chhankitek(CarbonImmutable $target, ?CacheRepository $cache = null)
    {
        return (new Chhankitek($target, $cache))->formatKhmerDate;
    }
}
