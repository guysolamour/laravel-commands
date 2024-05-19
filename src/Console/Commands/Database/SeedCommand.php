<?php


namespace Guysolamour\Command\Console\Commands\Database;


use Illuminate\Support\Arr;
use Guysolamour\Fsystem\Fsystem;

// class SeedCommand extends Command
class SeedCommand extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:db:seed
                             {--c|class= : The seeder to run }
                             {--all : Run all registered seed files }
                             {--force : Force the operation to run when in production }
                             ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with records';



    public function handle()
    {
        if ($this->option('all')){
            $this->runSeed();
            exit;
        }

        $seeders = Arr::wrap($this->option('class')) ?: $this->getAllSeeders();

        foreach ($seeders as $seeder) {
            $this->runSeed($seeder);
        }
    }


    private function runSeed(?string $class = null) :void
    {
        $options = [];

        if ($class){
            $options['--class'] =  $class;
        }

        if ($force = $this->option('force')){
            $options['--force'] = $force;
        }

        $this->call("db:seed", $options);
    }


    private function getAllSeeders(): array
    {
        $fsystem = new Fsystem();

        $files = collect($fsystem->allFiles(database_path('seeders')))
                    ->map(fn ($file) =>  $file->getFilenameWithoutExtension());

        if ($files->isEmpty()) {
            $this->line("No class available to run");
            exit;
        }

        return  $this->choice("Which class do you want to run ?", $files->toArray(), null, null, true);
    }
}
