<?php
namespace Guysolamour\Command\Console\Commands\Entity;


use Illuminate\Container\Container;
use Illuminate\Support\Str;


class BaseEntity
{
    public   $TPL_PATH = __DIR__ . '/../../../templates/entity';

    protected function parseName(string $name)
    {

        return $parsed = array(
            '{{namespace}}' => $this->getNamespace(),
            '{{pluralCamel}}' => Str::plural(Str::camel($name)),
            '{{pluralSlug}}' => Str::plural(Str::slug($name)),
            '{{pluralSnake}}' => Str::plural(Str::snake($name)),
            '{{pluralClass}}' => Str::plural(Str::studly($name)),
            '{{singularCamel}}' => Str::singular(Str::camel($name)),
            '{{singularSlug}}' => Str::singular(Str::slug($name)),
            '{{singularSnake}}' => Str::singular(Str::snake($name)),
            '{{singularClass}}' => Str::singular(Str::studly($name)),
        );
    }

    

    protected function writeFile($path,$content){
        if (!file_exists($path)) {
            file_put_contents($path, $content);
            return true;
        }

        return false;
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

    /**
     * @param  string $path
     */
    protected function createDirIfNotExists(string $path): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }


}

