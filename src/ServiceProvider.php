<?php

namespace Guysolamour\Command;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/command.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('command.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'command'
        );

        $this->app->bind('command', function () {
            return new Command();
        });
    }
}
