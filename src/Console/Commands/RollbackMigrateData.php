<?php

namespace Michielfb\DataMigrations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;

class RollbackMigrateData extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rollback-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the data migrations';

    protected $files;
    protected $resolver;

    /** @var  Migrator */
    protected $migrator;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        // @todo research how this can be altered to dependency injection.
        $this->migrator = \App::make('migrator.data');

        parent::__construct();
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
        $path = 'database/data-migrations';

        $this->migrator->rollback($path);

        foreach ($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }
    }
}