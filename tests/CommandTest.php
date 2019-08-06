<?php

namespace Guysolamour\Command\Tests;

use Guysolamour\Command\Facades\Command;
use Guysolamour\Command\ServiceProvider;
use Orchestra\Testbench\TestCase;

class CommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'command' => Command::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
