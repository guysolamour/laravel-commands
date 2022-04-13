<?php

namespace Guysolamour\Command\Console\Commands\Database;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;



abstract class BaseCommand extends Command
{

    protected const DEFAULT_CONNECTIONS = [
        'sqlite', 'mysql'
    ];

    /**
     *  Get the connection must be sqlite ou mysql
     */
    protected function getConnection()
    {
        $connection = $this->option('connection');
        if (in_array($connection, self::DEFAULT_CONNECTIONS)) {
            return $connection;
        }
        $this->error(sprintf("The [`%s`] connection is not allowed. Allowed values are [`%s`]", $connection, join(',', self::DEFAULT_CONNECTIONS)));
        exit;
    }



    public function getDatabaseName() :?string
    {
        return $this->argument('database') ?: config("database.connections.{$this->getConnection()}.database");
    }



    protected function getDatabaseCredentials(string $name) :string
    {
        if ($this->argument('database')) {
            return strtolower($this->option($name));
        }

        $credential = config("database.connections." . $this->option('connection') . ".$name");
        if(!$credential){
            $this->error("The {$name} credentials not found in " . $this->option('connection'));
            exit;
        }

        return strtolower($credential);
    }


    /**
     * Guest sqlite database name and give the full url if its a sqlite connection used
     * @param $schemaName
     * @return string
     */
    protected function guestName(string $schemaName): string
    {
        $nameWithExtension = Arr::last(explode('/', $schemaName));

        $name = Arr::first(explode('.', $nameWithExtension));

        return $name;
    }

    protected function SqliteFullPath(string $databaseName): string
    {
        $databaseName = $this->guestName($databaseName);
        return sprintf("%s/%s.sqlite", database_path(), $databaseName);
    }


    protected function getPDO() :\PDO
    {
        try {
            return new \PDO("mysql:host=localhost;port={$this->getDatabaseCredentials('port')}charset=utf8",$this->getDatabaseCredentials('username'), $this->getDatabaseCredentials('password'));
        } catch (\Throwable $th) {
            $this->error('Connection to mysql server failed. Check if login credentials given are correct.');
            exit;
        }
    }
}
