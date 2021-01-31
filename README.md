# Laravel Migration Builder

## Description

Programmatically define a table and column definitions and write a migration file. Useful for instance where you would like to create migration from an external source such as an api to store the data locally.

## Installation

`composer require blackbricksoftware/laravel-migration-builder --dev`

## Usage

```
<?php

namespace App\Console\Commands;

use BlackBrickSoftware\LaravelMigrationBuilder\Column;
use BlackBrickSoftware\LaravelMigrationBuilder\Migration;
use BlackBrickSoftware\LaravelMigrationBuilder\MigrationCreator;
use BlackBrickSoftware\LaravelMigrationBuilder\Table;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;

class CreateAccountMigration extends BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'migration-builder:create-account-migration';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create an account table migration';

  /**
   * The Composer instance.
   *
   * @var \Illuminate\Support\Composer
   */
  protected $composer;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(Composer $composer)
  {
    parent::__construct();

    $this->composer = $composer;
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {

    // $this->line('Authenticating with Salesforce....');
    // Forrest::authenticate();
    // $this->info('Authenticated!');

    // $objectName = $this->argument('objectName');

    // // https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_sobject_basic_info.htm
    // $objectDescription = Forrest::sobjects("$objectName/describe");
    // $objectMigration = new ObjectMigration($objectName, $objectDescription);
    // $objectMigration->create();

    $app = app();

    // see: Illuminate\Database\MigrationServiceProvider (we are using our own variant)
    $migrationCreator = new MigrationCreator($app['files'], $app->basePath('stubs'));

    // see: Illuminate\Database\Console\Migrations\MigrateMakeCommand
    $path = $this->getMigrationPath();

    $table = new Table('Account', [
      'timestamps' => false,
    ]);
    $column = new Column('id', 'integer', [
      'autoIncrement' => true,
    ]);
    $table->addColumn($column);

    $migration = new Migration('create_account_table', $path, $table, $migrationCreator);
    $file = $migration->writeMigration(true);

    $this->composer->dumpAutoloads();

    $this->info("Created Migration: $file");

    return 0;
  }

  /**
   * Get migration path (either specified by '--path' option or default location).
   *
   * @return string
   */
  protected function getMigrationPath()
  {
    if (!is_null($targetPath = $this->input->getOption('path'))) {
      return !$this->usingRealPath()
        ? $this->laravel->basePath() . '/' . $targetPath
        : $targetPath;
    }

    return parent::getMigrationPath();
  }
}
```

# Acknowledgement

- Andrew Hanks for this [article](https://medium.com/@andrewhanks2402/step-by-step-guide-to-laravel-package-development-82e2865fb278) on how to make a reddit package
- Iftekhar Rifat for this [repo](https://github.com/Agontuk/schema-builder) with a good starting spot for programatically creating migrations.