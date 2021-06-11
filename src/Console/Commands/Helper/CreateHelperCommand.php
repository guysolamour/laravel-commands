<?php

namespace Guysolamour\Command\Console\Commands\Helper;


use Illuminate\Support\Str;
use Guysolamour\Command\Console\Commands\Filesystem;
use Guysolamour\Command\Console\Commands\BaseCommand;

class CreateHelperCommand extends BaseCommand
{
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

    /**
     *  @var string
     */
    protected $provider_path;


    /**
     *  @var string
     */
    protected $name;


    /**
     *  @var string
     */
    protected $folder;


    /** @var Filesystem */
    protected $filesystem;



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name   = $this->getHelperName();
        $this->folder = $this->getHelperFolder();
        $this->provider_path = app_path('Providers/HelperServiceProvider.php');
        $this->filesystem = new Filesystem();

        $this->loadHelper();

        $this->loadHelperServiceProvider();
    }

    private function getHelperName() :string
    {
        return $this->argument('name');
    }

    private function getHelperFolder() :string
    {
        return $this->option('folder');
    }

    private function loadHelper()
    {
        $helper_path = app_path(sprintf('%s/%s.php', $this->folder, $this->name));

        if ($this->filesystem->exists($helper_path)) {
            $this->error("The [{$this->name}] helper already exists");
            return;
        }

        $helper_stub = $this->filesystem->compliedFile($this->getTemplatePath('/helper/helper.stub'));

        $this->filesystem->writeFile(
            $helper_path,
            $helper_stub
        );

        $this->info("{$this->name} file created at " . $helper_path);
    }

    private function loadHelperServiceProvider()
    {
        if ($this->filesystem->exists($this->provider_path)) {
            return;
        }

        $this->call('cmd:make:provider', [
            'name'       => 'Helper',
            '--register' => true,
        ]);

        $helper_provider = $this->filesystem->get($this->provider_path);

        $search = <<<TEXT
            public function register()
            {
        TEXT;

        $this->filesystem->replaceAndWriteFile(
            $helper_provider,
            $search,
            <<<TEXT
            $search
                    foreach (glob(app_path('$this->folder') . '/*.php') as \$file) {
                        require_once \$file;
                    }
            TEXT,
            $this->provider_path
        );

    }
}
