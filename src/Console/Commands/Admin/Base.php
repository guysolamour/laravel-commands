<?php
namespace Guysolamour\LaravelUsefulCommands\Console\Commands\Admin;


use Illuminate\Console\Command;

abstract class Base extends Command
{
    /**
     * @var
     */
    protected $admin;

    /**
     * Retrieve the admin model namespace
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function adminModel()
    {
        return config('useful-commands.admin_model');
    }

    public function __construct()
    {
        parent::__construct();
        $model = $this->adminModel();
        $this->admin = new $model();
    }
    /**
     * Get the emails of all admins
     *
     * @return array
     */
    protected function getAdminsEmail() :array
    {
        return $this->admin->all()->pluck('email')->toArray();
    }
}
