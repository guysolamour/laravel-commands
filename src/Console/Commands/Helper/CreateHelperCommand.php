<?php

namespace Guysolamour\Command\Console\Commands\Helper;


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
        $this->name = strtolower($this->argument('name'));
        $this->folder = ucfirst($this->option('folder'));

        $this->loadHelper();

        // HelperServiceProvider
        if (!$this->filesystem->exists($this->provider_path)) {
            $this->loadHelperServiceProvider();
        }

    }


    private function loadHelper()
    {
        $helper_path = app_path(sprintf('%s/%s.php', $this->folder, $this->name));

        if ($this->filesystem->exists($helper_path)) {
            $this->error("The [{$this->name}] helper already exists");
            return;
        }


        $helper_stub = $this->filesystem->get($this->template_path . '/helper/helper.stub');

        $this->compliedAndWriteFile(
            $helper_stub,
            $helper_path,
        );

        $this->info("{$this->name} file created at " . $helper_path);
    }


    private function loadHelperServiceProvider()
    {
        $this->call('cmd:make:provider', [
            'name'       => 'Helper',
            '--register' => true,
        ]);

        $helper_provider = $this->filesystem->get($this->provider_path);

        $search = <<<TEXT
            public function register()
            {
        TEXT;

        $this->replaceAndWriteFile(
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
