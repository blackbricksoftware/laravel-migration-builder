<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder\Commands;

use BlackBrickSoftware\LaravelMigrationBuilder\Column;
use BlackBrickSoftware\LaravelMigrationBuilder\Migration;
use BlackBrickSoftware\LaravelMigrationBuilder\MigrationCreator;
use BlackBrickSoftware\LaravelMigrationBuilder\Table;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;

class Example extends BaseCommand
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected string $signature = 'make:migration-builder-example
    {--path= : The location where the migration file should be created}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected string $description = 'Create an example migration from Laravel Migration Builder';

  /**
   * The Composer instance.
   *
   * @var \Illuminate\Support\Composer
   */
  protected Composer $composer;

  /**
   * Create a new command instance.
   *
   * @param Composer $composer
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

    $app = app();

    // see: Illuminate\Database\MigrationServiceProvider (we are using our own variant)
    $migrationCreator = new MigrationCreator($app['files'], $app->basePath('stubs'));

    // see: Illuminate\Database\Console\Migrations\MigrateMakeCommand
    $path = $this->getMigrationPath();

    $table = new Table('Account', [
      'timestamps' => false,
    ]);

    $idColumn = new Column('id', 'integer', [
      'autoIncrement' => true,
    ]);
    $table->addColumn($idColumn);

    $table->addColumn(new Column('name', 'string', [
      'length' => 255,
    ]));

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