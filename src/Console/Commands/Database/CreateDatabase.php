<?php

namespace Guysolamour\Command\Console\Commands\Database;

use Guysolamour\Fsystem\Fsystem;

class CreateDatabase extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:db:create';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create current database';

    protected string $connection;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->connection = $this->getConnection();

        $this->create();
    }


    private function create(): void
    {
        $schemaName = $this->getDatabaseName();

        $this->isSqliteDriver() ? $this->CreateSqliteDatabase($schemaName) : $this->CreateSqliteDatabase($schemaName);

        $this->info("The [$schemaName] database created successfully with [{$this->connection}] connection");
    }

    /**
     * @param $schemaName
     */
    private function CreateSqliteDatabase(string $path): void
    {
        $fsystem = new Fsystem();

        if ($fsystem->exists($path)){
            $this->error("{$path} database already exists");
            exit;
        }

        $fsystem->writeFile($path, null, false);
    }

    /**
     * @param $schemaName
     */
    private function CreateMysqlDatabase($schemaName): void
    {
        $charset = config("database.connections.{$this->connection}.charset", 'utf8mb4');
        $collation = config("database.connections.{$this->connection}.collation", 'utf8mb4_unicode_ci');
        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        $this->getPDO()->exec($query);
    }
}
