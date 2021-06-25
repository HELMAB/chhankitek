<?php

namespace Chhankitek\Chhankitek;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Chhankitek\Chhankitek\Skeleton\SkeletonClass
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
