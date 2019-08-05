<?php

namespace Guysolamour\LaravelUsefulCommands\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelUsefulCommands extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-useful-commands';
    }
}
