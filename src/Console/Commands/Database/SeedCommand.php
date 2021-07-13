<?php


namespace Guysolamour\Command\Console\Commands\Database;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Guysolamour\Command\Console\Commands\Filesystem;

class SeedCommand extends Command
{
    const SEEDERS_NAMESPACE_PREFIX = 'Database\\Seeders\\';

    /** @var Filesystem */
    protected  $filesystem;

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


    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem;
    }

    public function handle()
    {
        if ($this->option('all')){
            $this->runSeed();
            exit;
        }

        $seeders = Arr::wrap($this->option('class')) ?: $this->getSeedClass();

        foreach ($seeders as $seeder) {
            $this->runSeed($seeder);
        }
    }

    private function runSeed(?string $class = null) :void
    {
        $options = [];

        if ($class){
            $options['--class'] =  $this->getSeederWithNamespace($class);
        }

        if ($force = $this->option('force')){
            $options['--force'] = $force;
        }

        $this->call("db:seed", $options);
    }

    private function getSeederWithNamespace(string $seeder) :string
    {
        if (Str::startsWith($seeder, self::SEEDERS_NAMESPACE_PREFIX)){
            return $seeder;
        }

        return self::SEEDERS_NAMESPACE_PREFIX . $seeder;;
    }

    private function getSeedClass(): array
    {
        $files = collect($this->filesystem->allFiles(database_path('seeders')))
            ->map(fn ($file) => $file->getRelativePathname());

        if ($files->isEmpty()) {
            $this->line("No class available to run");
            exit;
        }

        $seeders =  $this->choice("Which class do you want to run ?", $files->toArray(), null, null, true);

        return array_map(function ($seeder) {
            return $this->removeExtension(str_replace('/', '\\', $seeder));
        }, $seeders);
    }

    private function removeExtension(string $file): string
    {
        return Str::beforeLast($file, '.');
    }
}
