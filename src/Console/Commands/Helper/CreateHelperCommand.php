<?php

namespace Guysolamour\Command\Console\Commands\Helper;


use Illuminate\Support\Str;
// use Guysolamour\Command\Console\Commands\fsystem;
use Guysolamour\Fsystem\Fsystem;
use Guysolamour\Command\Console\Commands\BaseCommand;

class CreateHelperCommand extends BaseCommand
{
    protected const HELPER_STUB = "/helper/helper.stub";

    protected const HELPER_SERVICE_PROVIDER = "HelperServiceProvider";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:make:helper {name}
                                {--f|folder=Helpers : folder name inside app directory }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create helper file';



    /** @var Fsystem */
    protected $fsystem;



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fsystem = new Fsystem();

        $this->createHelperFile();

        $this->loadHelperServiceProvider();
    }

    private function createHelperFile() {
        $path = app_path(sprintf('%s/%s.php', $this->option('folder'), $this->argument('name')));

        if ($this->fsystem->exists($path)) {
            $this->error("The [{$path}] helper already exists");
            return;
        }

        $stub = $this->fsystem->compliedFile($this->getTemplatePath(self::HELPER_STUB));

        $this->fsystem->writeFile(
            $path,
            $stub
        );

        $this->loadHelperServiceProvider();

        $this->info("{$path} file created.");
    }

    private function getHelperServiceProviderPath() : string {
        return app_path(sprintf('Providers/%s.php', self::HELPER_SERVICE_PROVIDER));
    }

    private function checkIfHelperServiceProviderExists() : bool {
        return $this->fsystem->exists($this->getHelperServiceProviderPath());
    }



    private function loadHelperServiceProvider()
    {
        if ($this->checkIfHelperServiceProviderExists()) {
            return;
        }

        $this->call('make:provider', [
            'name'  => self::HELPER_SERVICE_PROVIDER,
        ]);

        $path     = $this->getHelperServiceProviderPath();
        $folder   = $this->option('folder');
        $provider = $this->fsystem->get($path);

        $search = <<<TEXT
            public function register(): void
            {
        TEXT;

        $this->fsystem->replaceAndWriteFile(
            $provider,
            $search,
            <<<TEXT
            $search
                    foreach (glob(app_path('$folder') . '/*.php') as \$file) {
                        require_once \$file;
                    }
            TEXT,
            $path
        );

    }
}
