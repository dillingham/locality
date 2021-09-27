<?php

namespace Dillingham\Locality;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Dillingham\Locality\Commands\LocalityCommand;

class LocalityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('locality', function($app) {
            return new Locality();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/locality.php',
            'locality'
        );

        Blueprint::macro('addAddress', function() {
            $this->string('formatted_address')->nullable();
            $this->string('address_1')->nullable();
            $this->string('address_2')->nullable();
            $this->foreignId('admin_level_3_id')->nullable()->index();
            $this->foreignId('admin_level_2_id')->index();
            $this->foreignId('admin_level_1_id')->index();
            $this->foreignId('postal_code_id')->index();
            $this->foreignId('country_id')->index();

            return $this;
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
