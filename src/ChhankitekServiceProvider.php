<?php

namespace Asorasoft\Chhankitek;

use Illuminate\Support\ServiceProvider;

/**
 * Class ChhankitekServiceProvider
 * @package Asorasoft\Chhankitek
 */
class ChhankitekServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Optional methods to load your package assets
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('chhankitek', function () {
            return new Chhankitek;
        });
    }
}
