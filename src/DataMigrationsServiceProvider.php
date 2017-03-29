<?php

namespace Michielfb\DataMigrations;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Michielfb\DataMigrations\Repositories\DataMigrationRepository;

class DataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
            $this->registerMigrationFolder();
            $this->registerConfig();
        }
    }

    protected function registerMigrations()
    {
        $this->publishes([
            __DIR__.'/../assets/database/migrations' => database_path('migrations'),
        ], 'data-migrations');
    }

    protected function registerMigrationFolder()
    {
        $this->publishes([
            __DIR__.'/../assets/data-migrations' => database_path('data-migrations'),
        ], 'data-migrations');
    }

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../assets/config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();
        $this->registerMigrator();
        $this->registerArtisanCommands();
    }

    protected function registerRepository()
    {
        $this->app->singleton('migration.data.repository', function ($app) {
            $table = $app['config']['database.migrations_data'];

            return new DataMigrationRepository($app['db'], $table);
        });
    }

    protected function registerMigrator()
    {
        $this->app->singleton('migrator.data', function($app) {
            $repository = $app['migration.data.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });
    }

    protected function registerArtisanCommands()
    {
        $this->commands([
            \Michielfb\DataMigrations\Console\Commands\MakeMigrateData::class,
            \Michielfb\DataMigrations\Console\Commands\MigrateData::class,
            \Michielfb\DataMigrations\Console\Commands\RollbackMigrateData::class,
        ]);
    }
}
