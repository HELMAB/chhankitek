<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

use Illuminate\Support\Facades\Facade;

/**
 * Class ChhankitekFacade
 */
final class ChhankitekFacade extends Facade
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
