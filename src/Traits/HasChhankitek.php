<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Traits;

use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

trait HasChhankitek
{
    public function chhankiteck(CarbonImmutable $target)
    {
        return (new Chhankitek($target))->formatKhmerDate;
    }
}
