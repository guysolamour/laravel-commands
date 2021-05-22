<?php

namespace Guysolamour\Command\Console\Commands\Provider;

use Illuminate\Support\Str;
use Guysolamour\Command\Console\Commands\Filesystem;
use Guysolamour\Command\Console\Commands\BaseCommand;



Class CreateProviderCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:make:provider {name}
                                {--r|register : register provider in config app file}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create service provider file';


    /** @var string */
    protected $name;


    /** @var Filesystem */
    protected $filesystem;


    public function handle()
    {
        $this->info('Initiating...');

        $this->name = $this->getProviderName();

        $this->filesystem = new Filesystem($this->parseName());

        $this->loadProvider();
    }

    
    protected function loadProvider()
    {
        $providers_path = app_path('Providers/');

        $provider_stub = $this->filesystem->compliedFile($this->getTemplatePath('/provider/provider.stub'));

        $this->filesystem->writeFile(
            $providers_path . '/' .  $this->name . '.php',
            $provider_stub
        );

        $this->info("{$this->name} created at " . $providers_path);

        if ($this->option('register')) {
            $this->registerServiceProvider();
        }
    }

    protected function parseName() :array
    {
        return array_merge(parent::parseName(),[
            '{{providerName}}' => $this->getProviderName()
        ]);
    }

    private function getProviderName() :string
    {
        return Str::finish($this->argument('name'), 'ServiceProvider');
    }

    private function registerServiceProvider()
    {
        $config_app_path = config_path('app.php');
        $app = $this->filesystem->get($config_app_path);

        $search = sprintf('%s\Providers\AppServiceProvider::class,', $this->getNamespace());
        $replace = sprintf('%s\Providers\%s::class,', $this->getNamespace(), $this->name);

        $this->filesystem->replaceAndWriteFile(
            $app,
            $search,
            <<<TEXT
            $search
                    $replace
            TEXT,
            $config_app_path
        );

        $this->info("{$this->name} registered in  " . $config_app_path . " file");
    }
}
