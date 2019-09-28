<?php

namespace Guysolamour\Command\Console\Commands\Entity;

use Illuminate\Console\Command;



class CreateEntity extends Command
{

    protected const TYPES = [
        'string','text','boolean','date','datetime','decimal','float','enum','double','integer',
        'ipAdress','longText','mediumText','mediumInterger','image'
    ];

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {name}
                                {--s|slug= : The field to slugify}
                                {--nt|notimestamps : Determine if the model is not timestamped}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model, migration and seeder files';

    public function handle(){


        $this->info('Initiating...');

        $progress = $this->output->createProgressBar(2);

        $this->name = $this->argument('name');
        $this->timestamps = $this->option('notimestamps');
        $this->slug = is_string($this->option('slug')) ? strtolower($this->option('slug')) : $this->option('slug');

        $this->fields = $this->getFields();

        $progress->advance();


         // Models
        $this->info(PHP_EOL . 'Creating Model...');
        [$result,$model_path] = LoadModel::generate($this->name, $this->fields, $this->slug, $this->timestamps);
        $this->displayResult($result,$model_path);
        $progress->advance();

         // Migrations
        $this->info(PHP_EOL . 'Creating Migration...');
        [$result,$migration_path,$seed_path] = LoadMigration::generate($this->name, $this->fields, $this->slug, $this->timestamps);
        $this->displayResult($result,$migration_path);
        $this->displayResult($result,$seed_path);

         // update composer autoload for seeding
        exec('composer dump-autoload > /dev/null 2>&1');

        $progress->finish();



    }


    protected function displayResult(bool $result,string $path){
        if (!$result) {
            return $this->error(PHP_EOL . "The {$path} file already exists");
        }

        return $this->info( PHP_EOL . 'File created at ' . $path);

    }

     /**
     * @return array
     */
    private function getFields() :array
    {

        $fields = [];

        $fields[] = $this->ask('Field');
        $fields[] = $this->anticipate('Type', self::TYPES);

        if ($this->confirm('Add another field?')) {
            $fields =  array_merge($fields,$this->getFields());
        }

        return $fields;
    }
}
