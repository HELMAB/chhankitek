<?php

namespace Asorasoft\Chhankitek;

use Illuminate\Support\Facades\Facade;

/**
 * Class ChhankitekFacade
 * @package Asorasoft\Chhankitek
 */
class ChhankitekFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'chhankitek';
    }
}
