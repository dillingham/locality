<?php

namespace Dillingham\Locality;

use Dillingham\Locality\Commands\LocalityCommand;
use Illuminate\Support\ServiceProvider;

class LocalityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/locality.php',
            'locality'
        );
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'locality');

        if ($this->app->runningInConsole()) {
            $this->commands([
                LocalityCommand::class,
            ]);
        }
    }
}
