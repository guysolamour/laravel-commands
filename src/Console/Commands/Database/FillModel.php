<?php


namespace Guysolamour\Command\Console\Commands\Database;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Container\Container;

class FillModel extends Command
{

    private const EXCLUDE_FIELDS = ['id','created_at','updated_at'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commands:model:fill {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill the model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(){

        $argument = $this->argument('name');

        $parts = explode("/",$argument);

        $model = $this->getNamespace() ;



        foreach ($parts as $value) {
            $model .= '\\' . $value;
        }


        $table_name = $this->getTableName(end($parts));



        $table_fields = $this->getTableFields($table_name);

        new A;

        dd($model, $table_name,  $table_fields);

        $fields = [];

        foreach ($table_fields as $field) {
          $fields[$field] =   $this->ask('Enter ' . $field);
        }


        $model::create($fields);

        $this->info('Model filled succesfuly');

       // dd($model);
    }

    private function getTableName(string $name) :string{
        return strtolower(Str::plural($name));
    }

    private function getModelName() :string{
        return ucfirst($this->argument('name'));
    }

    private function getTableFields(string $table_name) :array {
        $table_fields = Schema::getColumnListing($table_name);

        return array_diff($table_fields,self::EXCLUDE_FIELDS);

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
