<?php

namespace Guysolamour\Command\Console\Commands\Database;


use Illuminate\Support\Facades\File;

class CreateDatabase extends BaseCommand
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:db:create
                            {database? : database name }
                            {--u|username=root : database user }
                            {--p|password=root : database password }
                            {--c|connection=mysql : database connection }
                            {--r|port=3306 : database password }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $connection = $this->getConnection();

        $this->create($connection);
    }



    private function create($connection)
    {
        $schemaName = $this->getDatabaseName($connection);

        switch ($connection) {
            case 'sqlite':
                $this->CreateSqliteDatabase($schemaName);
                break;
            case 'mysql':
                $this->CreateMysqlDatabase($schemaName);
                break;
        }

        $this->info("The [$schemaName] database created successfully with [{$connection}] connection");
    }

    /**
     * @param $schemaName
     */
    private function CreateSqliteDatabase($schemaName): void
    {
        $databaseName = $this->guestName($schemaName);
        $url = $this->SqliteFullPath($databaseName);
        if (File::exists($url)){
            $this->error("{$databaseName} database already exists");
            exit;
        }
        File::put($url, null);
    }

    /**
     * @param $schemaName
     */
    private function CreateMysqlDatabase($schemaName): void
    {
        $charset = config("database.connections.mysql.charset", 'utf8mb4');
        $collation = config("database.connections.mysql.collation", 'utf8mb4_unicode_ci');
        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        $this->getPDO()->exec($query);

    }

}
