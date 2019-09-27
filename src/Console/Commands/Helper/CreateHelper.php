<?php

namespace Guysolamour\Command\Console\Commands\Helper;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

class CreateHelper extends Command
{

    protected const TPL_PATH = __DIR__ . '/../../../templates/helper';

    protected $provider_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create helper file';

    protected $name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->provider_path = app_path('Providers/HelperServiceProvider.php');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = $this->argument('name');


        // HelperServiceProvider
        if (!file_exists($this->provider_path)) {

            $this->info(PHP_EOL . 'Creating HelperServiceProvider...');
            $service_provider_path = $this->loadHelperServiceProvider(self::TPL_PATH);
            $this->info('ServicePovider created at ' . $service_provider_path);
        }


        // Create Helper
        $this->info(PHP_EOL . 'Creating Helper...');
        $helper_path = $this->loadHelper(self::TPL_PATH);
        if($helper_path)
            $this->info('Helper created at ' . $helper_path);


    }

    protected function loadHelper($stub_path)
    {
        try {

            $helper_path = app_path('Helpers/'. strtolower($this->name) . '.php');

            if (file_exists($helper_path)) {
                $this->error("The {$this->name} helper already exists");
                return;
            }

            $dir = app_path('Helpers');
            if (!is_dir(app_path('Helpers'))) {
                mkdir($dir, 0755, true);
            }

            $stub = file_get_contents($stub_path . '/helper.stub');


            file_put_contents($helper_path, $stub);


            return $helper_path;


        }catch (\Exception $ex) {
            throw new \RuntimeException($ex->getMessage());
        }
    }

    protected function loadHelperServiceProvider($stub_path)
    {
        try {


            $stub = file_get_contents($stub_path . '/provider.stub');

            $data_map = $this->parseName();

            $provider = strtr($stub, $data_map);


            file_put_contents($this->provider_path, $provider);


            // register service provider

            $config_app_path = config_path('app.php');
            $provider = file_get_contents($config_app_path);

            //$data_map = $this->parseName();



            $route_mw_bait = "App\Providers\AppServiceProvider::class";
            $prefix = "App\Providers\HelperServiceProvider::class";



            $config = str_replace($route_mw_bait, $prefix , $provider);



            file_put_contents($config_app_path, $config);



            return $this->provider_path;

        } catch (\Exception $ex) {
            throw new \RuntimeException($ex->getMessage());
        }
    }

    /**
     * Parse guard name
     * Get the guard name in different cases
     * @param string $name
     * @return array
     */
    protected function parseName($name = null)
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
