<?php

namespace Guysolamour\LaravelUsefulCommands\Console\Commands\Admin;

use Creativeorange\Gravatar\Facades\Gravatar;

class Create extends Base
{

    private CONST PASSWORD_LENGTH = 6;

    private $isSafe = true;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--s|super : Give super admin role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a fresh administrator in database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask( 'Name');
        $email = $this->ask('Email');

        $password = $this->secret('Password');
        $password_confirm = $this->secret('Confirm');


        $safeValues = $this->validate($name,$email,$password,$password_confirm);

        if($this->isSafe){

            $this->create($safeValues);
            $this->info( "Administrator {$name} with {$email} created !");
        }
    }

    private function create(array $fields) {


        [$name,$email,$password] = $fields;


        return $this->admin::create([
			'name' => $name,
			'email' => $email,
			'avatar' => Gravatar::get($email),
			'is_super_admin' =>  $this->option('super'),
			'password' => $password,
		]);
    }

    private function validate(string $name, string $email, string $password, string $password_confirm) :?array
    {
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
             $this->isSafe = false;
             $this->error( "The email [`{$email}`] is not a valid email");

        }

        if (mb_strlen($password) < self::PASSWORD_LENGTH) {
            $this->isSafe = false;
            $this->error( "The password is short, must be more than ". self::PASSWORD_LENGTH . ' characters');
            return null;
        }
        if($password !== $password_confirm) {
            $this->isSafe = false;
            $this->error( "The password are not equal");
            return null;

        }


        return [$name,$email,$password];
    }
}
