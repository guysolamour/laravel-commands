<?php

namespace Guysolamour\Command\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Container\Container;


abstract class BaseCommand  extends Command
{
    /** @var string */
    private $template_path;




    public function __construct()
    {
        parent::__construct();

        $this->template_path = dirname(dirname(__DIR__)) . "/stubs";
    }


    protected function getTemplatePath(string $path = ''): string
    {
        $path = Str::start($path, '/');

        return $this->template_path .  $path;
    }

    protected function parseName() :array
    {
        return [
            '{{namespace}}'      =>  $this->getNamespace(),
        ];
    }

    /**
     * Get project namespace
     * Default: App
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = Container::getInstance()->getNamespace();

        return rtrim($namespace, '\\');
    }

}
