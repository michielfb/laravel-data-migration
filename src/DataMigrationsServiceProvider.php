<?php

namespace Michielfb\DataMigrations;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Michielfb\DataMigrations\Console\Commands\MakeDataMigrationCommand;
use Michielfb\DataMigrations\Console\Commands\MigrateDataCommand;
use Michielfb\DataMigrations\Console\Commands\RollbackMigrateDataCommand;
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
            $this->registerBinds();
            $this->registerMigrationDirectory();
            $this->registerConfig();
            $this->registerArtisanCommands();
        }
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
    private function registerMigrationDirectory(): void
    {
        $this->publishes([
            __DIR__.'/../assets/data-migrations' => database_path('data-migrations'),
        ], 'data-migrations');
    }

    /**
     * Register configuration file.
     *
     * @return void
     */
    private function registerConfig(): void
    {
        $this->publishes([
            __DIR__.'/../assets/config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations');
    }

    private function registerArtisanCommands(): void
    {
        $this->commands([
            MakeDataMigrationCommand::class,
            MigrateDataCommand::class,
            RollbackMigrateDataCommand::class,
        ]);
    }
}
