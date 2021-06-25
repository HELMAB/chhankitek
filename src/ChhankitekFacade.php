<?php

namespace Asorasoft\Chhankitek;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Asorasoft\Chhankitek\Skeleton\SkeletonClass
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
