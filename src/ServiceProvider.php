<?php

namespace Guysolamour\Command;


use Guysolamour\Command\Console\Commands\Admin\Edit;
use Guysolamour\Command\Console\Commands\Admin\Create;
use Guysolamour\Command\Console\Commands\Admin\Delete;
use Guysolamour\Command\Console\Commands\Database\FillModel;
use Guysolamour\Command\Console\Commands\Entity\CreateEntity;
use Guysolamour\Command\Console\Commands\Helper\CreateHelper;
use Guysolamour\Command\Console\Commands\Database\DropDatabase;
use Guysolamour\Command\Console\Commands\Database\CreateDatabase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/command.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('command.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Create::class,
                Delete::class,
                Edit::class,
                CreateDatabase::class,
                DropDatabase::class,
                FillModel::class,
                CreateHelper::class,
                CreateEntity::class,
            ]);
        }
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

