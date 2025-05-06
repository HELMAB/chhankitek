<?php

declare(strict_types=1);

use Asorasoft\Chhankitek\Chhankitek;
use Carbon\CarbonImmutable;

if (! function_exists('toLunarDate')) {
    function toLunarDate(CarbonImmutable $target)
    {
        return (new Chhankitek($target))->formatKhmerDate;
    }
}
