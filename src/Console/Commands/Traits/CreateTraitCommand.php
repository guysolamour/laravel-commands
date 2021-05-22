<?php

namespace Guysolamour\Command\Console\Commands\Traits;


use Illuminate\Support\Str;
use Guysolamour\Command\Console\Commands\Filesystem;
use Guysolamour\Command\Console\Commands\BaseCommand;

class CreateTraitCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:make:trait {name}
                                {--f|folder=Traits : folder name inside app directory }
                            ';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create trait';


    /**
     *  @var string
     */
    protected $name;


    /**
     *  @var string
     */
    protected $folder;

    /** @var Filesystem */
    protected $filesystem;



    public function handle()
    {
        $this->info('Initiating...');

        $this->name       = $this->getTraitName();
        $this->folder     = $this->getFolderName();

        $this->filesystem = new Filesystem($this->parseName());

        // dd($this->filesystem->data_map);

        $this->loadTrait();
    }

    private function getTraitName() :string
    {
        return Str::ucfirst($this->argument('name'));
    }

    private function getFolderName() :string
    {
        return Str::ucfirst($this->option('folder'));
    }


    public function loadTrait()
    {
        $traits_path = app_path("{$this->folder}/{$this->name}.php");

        if ($this->filesystem->exists($traits_path)) {
            $this->error("The [{$this->name}] trait already exists");
            return;
        }

        $trait_stub = $this->filesystem->compliedFile($this->getTemplatePath('/trait/trait.stub'));

        $this->filesystem->writeFile($traits_path, $trait_stub);

        $this->info("{$this->name} trait created at " . $traits_path);
    }


    protected function parseName($name = null): array
    {
        return array_merge(parent::parseName(), [
            '{{traitName}}'    => $this->name,
            '{{traitsFolder}}' => $this->folder,
        ]);
    }
}
