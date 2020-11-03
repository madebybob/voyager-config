<?php

namespace MadeByBob\VoyagerConfig;

use Illuminate\Support\ServiceProvider;
use MadeByBob\VoyagerConfig\Console\Commands\VoyagerExportCommand;
use MadeByBob\VoyagerConfig\Console\Commands\VoyagerClearCommand;
use MadeByBob\VoyagerConfig\Console\Commands\VoyagerImportCommand;
use Illuminate\Support\Facades\Config;

class VoyagerConfigServiceProvider extends ServiceProvider
{

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__.'/../config/voyager-config.php' => config_path('voyager-config.php'),
    ]);

    $this->bootCommands();
  }
  
  /**
   * Sub of the `boot` function which registers all commands
   * 
   * @return void
   */
  public function bootCommands() 
  {
    if ($this->app->runningInConsole()) {
      $this->commands([
          VoyagerExportCommand::class,
          VoyagerClearCommand::class,
          VoyagerImportCommand::class
      ]);
    }
  }

  /**
   * Register the application services.
   *
   * @return void
   */  
  public function register()
  {
    $this->app->singleton(VoyagerConfig::class, function () {
      return new VoyagerConfig();
    });

    $this->app->alias(VoyagerConfig::class, 'voyager-config');

    $this->mergeConfigFrom(
      __DIR__.'/../config/voyager-config.php', 'voyager-config'
    );
  }

}
