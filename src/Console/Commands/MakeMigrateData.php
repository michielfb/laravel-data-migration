<?php

namespace Michielfb\DataMigrations\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class MakeMigrateData extends MigrateMakeCommand
{
    protected $signature = 'make:data-migration {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
        {--path= : The location where the migration file should be created.}';

    protected $description = 'Create a new data migration file';

    protected function getMigrationPath()
    {
        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'data-migrations';
    }
}