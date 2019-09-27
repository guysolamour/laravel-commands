<?php
namespace Guysolamour\Command\Console\Commands\Admin;


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
        return config('command.admin_model');
    }

    public function __construct()
    {
        parent::__construct();
        $model = $this->adminModel();

        if (!class_exists($model)) {
            return;
        };
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
