<?php

use Asorasoft\Chhankitek\Chhankitek;
use Carbon\Carbon;

if (! function_exists('toLunarDate')) {
    function toLunarDate(Carbon $target)
    {
        return (new Chhankitek($target))->formatKhmerDate;
    }
}
