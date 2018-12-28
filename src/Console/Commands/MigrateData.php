<?php

namespace Michielfb\DataMigrations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;

class MigrateData extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the data migrations';


    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->migrator = \App::make('migrator.data');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        // Get a path to the migration scripts
        // @todo add this as an option to the command.
        $path = $this->getMigrationPath();

        $this->migrator->run($path);
    }

    protected function getMigrationPath()
    {
        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'data-migrations';
    }
}
