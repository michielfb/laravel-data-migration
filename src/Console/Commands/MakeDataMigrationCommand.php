<?php

declare(strict_types=1);

namespace Michielfb\DataMigrations\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class MakeDataMigrationCommand extends MigrateMakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:data-migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    protected $description = 'Create a new data migration file';

    /**
     * Get migration path.
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        return $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'data-migrations';
    }
}
