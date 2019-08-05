<?php

namespace Guysolamour\LaravelUsefulCommands\Tests;

use Guysolamour\LaravelUsefulCommands\Facades\LaravelUsefulCommands;
use Guysolamour\LaravelUsefulCommands\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelUsefulCommandsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-useful-commands' => LaravelUsefulCommands::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
