<?php

namespace Dillingham\Locality;

use Dillingham\Locality\Http\Controllers\OptionController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LocalityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('locality', function ($app) {
            return new Locality();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/locality.php',
            'locality'
        );

        Blueprint::macro('addAddress', function () {
            $this->string('formatted_address')->nullable();
            $this->string('address_1')->nullable();
            $this->string('address_2')->nullable();
            $this->foreignId('admin_level_3_id')->nullable()->index();
            $this->foreignId('admin_level_2_id')->index();
            $this->foreignId('admin_level_1_id')->index();
            $this->foreignId('postal_code_id')->index();
            $this->foreignId('country_code_id')->index();

            return $this;
        });

        Route::macro('localityDependentOptions', function () {
            Route::get('locality/country_codes', [OptionController::class, 'countryCodes']);
            Route::get('locality/admin_level_1', [OptionController::class, 'adminLevel1']);
            Route::get('locality/admin_level_2', [OptionController::class, 'adminlevel2']);
            Route::get('locality/admin_level_3', [OptionController::class, 'adminLevel3']);
            Route::get('locality/postal_codes', [OptionController::class, 'postalCodes']);
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
