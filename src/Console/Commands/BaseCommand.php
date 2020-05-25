<?php

namespace Guysolamour\Command\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;


abstract class BaseCommand  extends Command
{

    /**
     * @var string
     */
    protected $template_path = '';


    protected $filesystem;


    public function __construct()
    {
        parent::__construct();

        $this->template_path = (dirname(dirname(__DIR__))) . "/templates";
        $this->filesystem = new Filesystem;
    }


    /**
     * Parse guard name
     * Get the guard name in different cases
     * @param string $name
     * @return array
     */
    protected function parseName($name = null) :array
    {
        return [
            '{{namespace}}'      =>  $this->getNamespace(),
            // '{{pluralCamel}}'    =>  Str::plural(Str::camel($name)),
            // '{{pluralSlug}}'     =>  Str::plural(Str::slug($name)),
            // '{{pluralSnake}}'    =>  Str::plural(Str::snake($name)),
            // '{{pluralClass}}'    =>  Str::plural(Str::studly($name)),
            // '{{singularCamel}}'  =>  Str::singular(Str::camel($name)),
            // '{{singularSlug}}'   =>  Str::singular(Str::slug($name)),
            // '{{singularSnake}}'  =>  Str::singular(Str::snake($name)),
            // '{{singularClass}}'  =>  Str::singular(Str::studly($name)),
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


    /**
     * @param string|array $files
     * @param string $path
     * @return void
     */
    protected function compliedAndWriteFile($files, string $path): void
    {

        if (is_array($files)) {
            foreach ($files as $file) {
                $this->compliedAndWriteFile($file, $path);
            }
            return;
        }

        $data_map = $this->parseName();

        $stub = $this->isSingleFile($files) ? $files : $this->filesystem->get($files->getRealPath());

        $this->createDirectoryIfNotExists(
            $path,
            !$this->isSingleFile($files)
        );
        $complied = strtr($stub, $data_map);

        $this->writeFile(
            $complied,
            $this->isSingleFile($files) ? $path : $path . '/' . $files->getFilenameWithoutExtension() . '.php'
        );
    }


    protected function compliedAndWriteFileRecursively($files, string $path)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->compliedAndWriteFileRecursively($file, $path);
            }
            return;
        }

        $this->compliedAndWriteFile(
            $this->filesystem->get($files),
            $path . '/' . $files->getRelativePath() .  '/' . $files->getFilenameWithoutExtension() . '.php'
        );
    }

    /**
     * @param string|array $files
     * @param string $search
     * @param string $path
     * @return void
     */
    protected function replaceAndWriteFile($files, string $search, $replace, string $path)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->replaceAndWriteFile($file, $search, $replace, $path);
            }
            return;
        }

        $stub = $this->isSingleFile($files) ? $files : $this->filesystem->get($files->getRealPath());
        // $stub = $this->filesystem->get($files->getRealPath());
        $this->createDirectoryIfNotExists(
            $path,
            !$this->isSingleFile($files)
        );
        $complied = str_replace($search, $replace,  $stub);

        $this->writeFile(
            $complied,
            $this->isSingleFile($files) ? $path : $path . '/' . $files->getFilenameWithoutExtension() . '.php'
        );
    }


    /**
     * Permet de créer un dossier
     * @param string $path
     * @param boolean $folder Permet de savoir si le chemin passé est un dossier ou fichier
     * @return void
     */
    protected function createDirectoryIfNotExists(string $path, bool $folder = true): void
    {

        $dir = $folder ? $path : $this->filesystem->dirname($path);

        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->makeDirectory($dir, 0755, true);
        }
    }

    /**
     * @param mixte $compiled
     * @param string $path
     * @return void
     */
    protected function writeFile($compiled, string $path): void
    {
        // dd($path, $compiled);
        $this->filesystem->put(
            $path,
            $compiled
        );
    }


    protected function isSingleFile($file): bool
    {
        return is_string($file);
    }

}
