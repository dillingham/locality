<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\LocalityServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LocalityServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migrations = [
            __DIR__.'/../database/migrations/create_countries_table.php.stub',
            __DIR__.'/../database/migrations/create_admin_level_1_table.php.stub',
            __DIR__.'/../database/migrations/create_admin_level_2_table.php.stub',
            __DIR__.'/../database/migrations/create_admin_level_3_table.php.stub',
            __DIR__.'/../database/migrations/create_postal_codes_table.php.stub',
            __DIR__.'/Fixtures/create_profiles_table.php.stub',
        ];

        foreach ($migrations as $migration) {
            $migration = include $migration;
            $migration->up();
        }
    }
}
