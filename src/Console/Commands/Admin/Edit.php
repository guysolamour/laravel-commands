<?php

namespace Guysolamour\Command\Console\Commands\Admin;

use Creativeorange\Gravatar\Facades\Gravatar;

class Edit extends Base
{
    private CONST PASSWORD_LENGTH = 6;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:edit {email?} {--s|super : Give super admin role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit administrator credentials';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get the admin mail
        $email = $this->argument('email');

        if (is_null($email)) {
            $email = $this->anticipate('Give the administrator email', $this->getAdminsEmail());
        }


        // validate the email
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $this->error( "The email [`{$email}`] is not a valid email");
            return null;
        }

        // retrieve the admin in database
        $admin = $this->admin::where('email', $email)->firstOrFail();

        $new_name = $this->confirm('Do you want to change the name?') ? $this->ask('Give the new name') : null;

        // if no the variable is still available
        $new_password= null;
        if ($this->confirm('Do you want to change the password?')) {
            $new_password = $this->secret('Give the new password');
            $confirm_password = $this->secret('Confirm password');

            // check if the password are same
            if ($new_password !== $confirm_password) {
                $this->error( "The password are not equal");
                return null;
            }

        }

        $new_email = null;
        if ($this->confirm('Do you want to change the email?')) {
            $new_email = $this->ask('Give the new email');
            if(!filter_var($new_email,FILTER_VALIDATE_EMAIL)){
                $this->error( "The [{$new_email}] is not a valid email");
                return null;
            }
        }


        // validate the new inputs
        [$new_name,$new_email,$new_password] = $this->validate($new_name,$new_email,$new_password);

        $cleanData = $this->getCleanData($new_name,$new_email,$new_password);

        // update the admin
        $admin->update($cleanData);

        $this->info( 'Administrator '  .$admin->name .' updated !');


    }

    private function validate( $name,  $email,  $password) :array
    {
        if(!is_null($email)){
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $this->error( "The [{$email}] is not a valid email");
            }
        }

        if (!is_null($password)) {
            if (mb_strlen($password) < self::PASSWORD_LENGTH) {
                $this->error( "The password is short, must be more than ". self::PASSWORD_LENGTH . ' characters');
            }
        }


        return [$name,$email,$password];
    }


    private function getCleanData($new_name,$new_email,$new_password) :array
    {
        $return = [];

        if(!is_null($new_name)) $return['name'] = $new_name;

        if(!is_null($new_password)) $return['password'] = $new_password;
        if(!is_null($new_email)) {
            $return['email'] = $new_email;
            $return['avatar'] = Gravatar::get($new_email);
        }

        $return['is_super_admin'] = $this->option('super');
        return $return;
    }
}
