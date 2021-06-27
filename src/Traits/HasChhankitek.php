<?php

namespace Asorasoft\Chhankitek\Traits;

use Asorasoft\Chhankitek\Chhankitek;
use Carbon\Carbon;

trait HasChhankitek
{
    public function chhankiteck(Carbon $target)
    {
        return (new Chhankitek($target))->formatKhmerDate;
    }
}
