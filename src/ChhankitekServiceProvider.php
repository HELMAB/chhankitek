<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek;

use Asorasoft\Chhankitek\Commands\VisakBocheaCheckCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Class ChhankitekServiceProvider
 */
final class ChhankitekServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                VisakBocheaCheckCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
    }
}
