<?php

namespace Michielfb\DataMigrations;

use Illuminate\Support\ServiceProvider;
use Michielfb\DataMigrations\Console\Commands\MakeDataMigrationCommand;
use Michielfb\DataMigrations\Console\Commands\MigrateDataCommand;
use Michielfb\DataMigrations\Console\Commands\RollbackMigrateDataCommand;
use Michielfb\DataMigrations\Repositories\DataMigrationRepository;

class DataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Boot application..
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->registerBinds();
            $this->publishDataMigrationDirectory();
            $this->publishMigrationDirectory();
            $this->registerArtisanCommands();
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    public function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../assets/config/data-migrations.php',
            'data-migrations'
        );
    }

    /**
     * Register binds.
     *
     * @return void
     */
    private function registerBinds(): void
    {
        $this->app->singleton(MakeDataMigrationCommand::class, function ($app) {
            return new MakeDataMigrationCommand(
                $app['migration.creator'],
                $app['composer']
             );
        });

        $this->app->singleton('migration.data.repository', function ($app) {
            return new DataMigrationRepository(
                $app['db'],
                $app['config']['data-migrations']['migrations_data']
            );
        });

        $this->app->singleton(DataMigrator::class, function($app) {
            return new DataMigrator(
                $app['migration.data.repository'],
                $app['db'],
                $app['files']
            );
        });
    }

    /**
     * Register data migrations directory.
     *
     * @return void
     */
    private function publishDataMigrationDirectory(): void
    {
        $this->publishes([
            __DIR__.'/../assets/data-migrations' => database_path('data-migrations'),
        ], 'data-migrations');
    }

    /**
     * Publish migrations directory.
     *
     * @return void
     */
    private function publishMigrationDirectory(): void
    {
        $this->publishes([
            __DIR__.'/../assets/database/migrations' => database_path('migrations'),
        ], 'data-migrations');
    }

    /**
     * Register configuration file.
     *
     * @return void
     */
    private function publishConfig(): void
    {

        $this->publishes([
            __DIR__.'/../assets/config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations');
    }

    /**
     * Register artisan commands.
     *
     * @return void
     */
    private function registerArtisanCommands(): void
    {
        $this->commands([
            MakeDataMigrationCommand::class,
            MigrateDataCommand::class,
            RollbackMigrateDataCommand::class,
        ]);
    }
}
