<?php

namespace Guysolamour\Command\Console\Commands\Traits;


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




    public function __construct()
    {
        parent::__construct();
        $this->provider_path = app_path('Providers/HelperServiceProvider.php');
    }



    public function handle()
    {
        $this->info('Initiating...');

        $this->name = ucfirst($this->argument('name'));

        $this->folder = ucfirst($this->option('folder'));

        $this->loadTrait();
    }


    public function loadTrait()
    {
        $traits_path = app_path("{$this->folder}/{$this->name}.php");

        if ($this->filesystem->exists($traits_path)) {
            $this->error("The [{$this->name}] trait already exists");
            return;
        }

        $trait_stub = $this->filesystem->get($this->template_path . '/trait/trait.stub');

        $this->compliedAndWriteFile(
            $trait_stub,
            $traits_path,
        );

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
