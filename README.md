# Data Migrations for Laravel

This package allows to migrate data the same way as regular database structure migrations.

### Installation

**Composer**
```shell
composer require michielfb/data-migrations
```

### Configuration

1. Register the ServiceProvider in your config/app.php service provider list.

```php
\Michielfb\DataMigrations\DataMigrationsServiceProvider::class
```

2. Add a `migrations_data` section to your config/database.php array. Add a table name. For example `data_migrations`.

```php
return [
    ...
    'migrations_data' => 'data_migrations',
    ...
];
```

3. Create a new folder called `data-migrations` in your \<root>/database folder.

4. Create a regular migration to add the data migrations table.

```php
Schema::create('data_migrations', function (Blueprint $table) {
    $table->string('migration');
    $table->integer('batch');
});
```

### Usage

The following artisan commands are available.

`make:data-migration` creates a new data migration.

`migrate:data` runs the data migration

`migrate:rollback-data` rolls back the migration.

