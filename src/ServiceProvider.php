<?php

namespace Guysolamour\Command;

use Guysolamour\Command\Console\Commands\Database\DumpCommand;
use Guysolamour\Command\Console\Commands\Database\SeedCommand;
use Guysolamour\Command\Console\Commands\Database\DropDatabase;
use Guysolamour\Command\Console\Commands\Database\CreateDatabase;
use Guysolamour\Command\Console\Commands\Helper\CreateHelperCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/command.php';

    public function boot()
    {
        // $this->publishes([
        //     self::CONFIG_PATH => config_path('command.php'),
        // ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDatabase::class,
                DropDatabase::class,
                CreateHelperCommand::class,
                SeedCommand::class,
                DumpCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'command'
        );
    }
}
