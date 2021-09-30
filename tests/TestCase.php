<?php

namespace Dillingham\Locality\Tests;

use Dillingham\Locality\LocalityServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Model::preventLazyLoading();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Dillingham\\Locality\\Tests\\Fixtures\\'.class_basename($modelName).'Factory'
        );
    }

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
            __DIR__.'/Fixtures/create_profiles_table.php',
        ];

        foreach ($migrations as $migration) {
            $migration = include $migration;
            $migration->up();
        }
    }
}
