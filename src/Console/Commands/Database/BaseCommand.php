<?php

namespace Guysolamour\Command\Console\Commands\Database;


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
        $connection = config('database.default');

        if (in_array($connection, self::DEFAULT_CONNECTIONS)) {
            return $connection;
        }
        $this->error(sprintf("The [`%s`] connection is not allowed. Allowed values are [`%s`]", $connection, join(',', self::DEFAULT_CONNECTIONS)));
        exit;
    }



    public function getDatabaseName() :?string
    {
        return $this->getDatabaseCredentials('database');
    }

    protected function isSqliteDriver(): bool
    {
        return $this->getDatabaseCredentials('driver') === self::DEFAULT_CONNECTIONS[0];

    }

    protected function isNotSqliteDriver(): bool
    {
        return !$this->isSqliteDriver();

    }



    protected function getDatabaseCredentials(string $key) :string
    {

        $credential = config("database.connections." . $this->getConnection() . ".$key");

        if(!$credential){
            $this->error("The {$key} credentials not found in " . $this->getConnection());
            exit;
        }

        return $credential;
    }





    protected function getPDO() :\PDO
    {
        $statement = sprintf("%s:host=%s;port=%scharset=%s", $this->getConnection(), $this->getDatabaseCredentials('host'), $this->getDatabaseCredentials('port'), $this->getDatabaseCredentials('charset'));

        try {
            return new \PDO($statement,$this->getDatabaseCredentials('username'), $this->getDatabaseCredentials('password'));
        } catch (\Throwable $th) {
            $this->error('Connection to mysql server failed. Check if login credentials given are correct.');
            exit;
        }
    }
}
