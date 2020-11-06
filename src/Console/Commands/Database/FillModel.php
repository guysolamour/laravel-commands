<?php


namespace Guysolamour\Command\Console\Commands\Database;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Container\Container;

class FillModel extends Command
{

    private const EXCLUDE_FIELDS = ['id', 'created_at', 'updated_at'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:model:fill {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill model';


    public function handle()
    {

        $argument = $this->argument('model');

        $parts = explode("/", $argument);


        $model = $this->getNamespace();

        if (1 === count($parts) && !empty($model_folder  = config('command.models_folder', ''))) {
            $model .= sprintf("\%s\%s", $model_folder, ucfirst(end($parts)));
        } else {
            foreach ($parts as $value) {
                $model .= '\\' . ucfirst($value);
            }
        }

        // check if model exists
        if (!class_exists($model)) {
            $this->error(
                sprintf("The [%s] model does not exists", $model)
            );
            return;
        }

        $table_name = $this->getTableName(end($parts));

        $table_fields = $this->getTableFields($table_name);

        $model = new $model;

        foreach ($table_fields as $field) {
            if ($field === 'password') {
                $model->$field =   $this->secret('Enter ' . $field);
            } else {
                $model->$field =   $this->ask('Enter ' . $field);
            }
        }

        $model->save();


        $this->info("Model filled succesfuly");
    }


    /**
     * @param string $name
     * @return string
     */
    private function getTableName(string $name): string
    {
        return strtolower(Str::plural($name));
    }

    /**
     * @return string
     */
    private function getModelName(): string
    {
        return ucfirst($this->argument('name'));
    }

    /**
     * @param string $table_name
     * @return array
     */
    private function getTableFields(string $table_name): array
    {
        $table_fields = Schema::getColumnListing($table_name);

        return array_diff($table_fields, self::EXCLUDE_FIELDS);
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
