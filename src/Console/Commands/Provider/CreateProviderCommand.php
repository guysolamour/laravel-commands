<?php

namespace Guysolamour\Command\Console\Commands\Provider;

use Illuminate\Support\Str;
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


    /**
     * @var string
     */
    protected $name = '';




    public function handle()
    {
        $this->info('Initiating...');


        $this->name = $this->getProviderName();

        $this->loadProvider();
    }


    protected function loadProvider()
    {

        $providers_path = app_path('Providers/');


        $provider_stub = $this->filesystem->get($this->template_path . '/provider/provider.stub');


        $this->compliedAndWriteFile(
            $provider_stub,
            $providers_path . '/' .  $this->name . '.php',
        );

        $this->info("{$this->name} created at " . $providers_path);


        if ($this->option('register')) {
            $this->registerServiceProvider();
        }

    }

    protected function parseName($name = null) :array
    {
        return array_merge(parent::parseName(),[
            '{{providerName}}' => $this->getProviderName()
        ]);
    }


    private function getProviderName()
    {
        return Str::finish($this->argument('name'), 'ServiceProvider');
    }

    private function registerServiceProvider()
    {
        $config_app_path = config_path('app.php');
        $app = $this->filesystem->get($config_app_path);


        $search = sprintf('%s\Providers\AppServiceProvider::class,', $this->getNamespace());
        $replace = sprintf('%s\Providers\%s::class,', $this->getNamespace(), $this->name);

        $this->replaceAndWriteFile(
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
