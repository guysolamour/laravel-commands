<?php

namespace Guysolamour\LaravelUsefulCommands;



use Guysolamour\LaravelUsefulCommands\Console\Commands\Admin\Create;
use Guysolamour\LaravelUsefulCommands\Console\Commands\Admin\Delete;
use Guysolamour\LaravelUsefulCommands\Console\Commands\Admin\Edit;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/useful-commands.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('useful-commands.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Create::class,
                Delete::class,
                Edit::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'useful-commands'
        );

        $this->app->bind('useful-commands', function () {
            return new LaravelUsefulCommands();
        });
    }
}
