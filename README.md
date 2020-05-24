# Data Migrations for Laravel

This package allows to migrate data the same way as regular database structure migrations.

### Installation

```shell
composer require michielfb/data-migrations
php artisan vendor:publish --tag=data-migrations
php artisan migrate
```

### Usage

The following artisan commands are available.

`make:data-migration` creates a new data migration.

`migrate:data` runs the data migration

`migrate:rollback-data` rolls back the migration.
