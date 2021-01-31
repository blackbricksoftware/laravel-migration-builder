<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder;

use Illuminate\Support\ServiceProvider;

class LaravelMigrationBuilderServiceProvider extends ServiceProvider
{
  
  /**
   * Publishes configuration file.
   *
   * @return  void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/../config/migration_builder.php' => config_path('migration_builder.php'),
    ], 'laravel-migration-builder-config');
  }

  /**
   * Make config publishment optional by merging the config from the package.
   *
   * @return  void
   */
  public function register()
  {
    // config
    $this->mergeConfigFrom(
      __DIR__ . '/../config/migration_builder.php',
      'migration_builder'
    );
    // commands
    $this->commands([
      Commands\Example::class
    ]);
  }
}
