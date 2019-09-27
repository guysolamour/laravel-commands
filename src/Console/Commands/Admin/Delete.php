<?php

namespace Guysolamour\Command\Console\Commands\Admin;


class Delete extends Base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:delete {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete administrator in database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (is_null($email)) {
            $email = $this->anticipate('Give the administrator email', $this->getAdminsEmail());
        }


	    $this->line( 'Deleting administrator');
	    $admin = $this->admin::where('email',$email)->first();
        if (!$admin){
            $this->error( "The admin with [`{$email}`] not found");
            return null;
        }
	    $admin->delete();
	    $this->info( 'Administrator '  . $email .' deleted !');
    }


}
