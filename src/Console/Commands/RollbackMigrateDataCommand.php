<?php

declare(strict_types=1);

namespace Michielfb\DataMigrations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Michielfb\DataMigrations\DataMigrator;

class RollbackMigrateDataCommand extends Command
{
    use ConfirmableTrait;

    /** @var string */
    protected $signature = 'migrate:rollback-data';

    /** @var string */
    protected $description = 'Run the data migrations';

    /** @var  Migrator */
    protected $migrator;

    /**
     * Constructor.
     *
     * @param DataMigrator $migrator
     */
    public function __construct(DataMigrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->migrator->rollback(
            $this->getMigrationPath()
        );
    }

    /**
     * Get migration path.
     *
     * @return array
     */
    protected function getMigrationPath(): array
    {
        return [$this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'data-migrations'];
    }
}
