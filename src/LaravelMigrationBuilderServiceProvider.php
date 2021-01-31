<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder;

use Illuminate\Support\ServiceProvider;

class LaravelLogEnhancerServiceProvider extends ServiceProvider
{
  /**
   * Publishes configuration file.
   *
   * @return  void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/../config/laravel_migration_builder.php' => config_path('laravel_migration_builder.php'),
    ], 'laravel-migration-builder-config');
  }
  /**
   * Make config publishment optional by merging the config from the package.
   *
   * @return  void
   */
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/../config/laravel_migration_builder.php',
      'laravel_migration_builder'
    );
  }
}
