<?php

namespace Guysolamour\LaravelUsefulCommands\Console\Commands\Db;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait DbTrait
{
    private $DEFAULT_CONNECTIONS = [
        'sqlite','mysql'
    ];

    /**
     *  Get the connection must be sqlite ou mysql
     */
    private function getConnection()
    {
        $connection = config('database.default');
        if (in_array($connection,$this->DEFAULT_CONNECTIONS)){
            return $connection;
        }
        $this->error("The connection ['{$connection}'] must be mysql or sqlite");
        exit;
    }

    /**
     * @param $connection
     * @return string
     */
    private function getSchemaName(string $connection) :string
    {
        return $this->argument('name') ?: config("database.connections.$connection.database");
    }


    /**
     * Guest sqlite database name and give the full url if its a sqlite connection used
     * @param $schemaName
     * @return string
     */
    private function guestName(string $schemaName) :string
    {
        $nameWithExtension = Arr::last(explode('/',$schemaName));

        $name = Arr::first(explode('.',$nameWithExtension));

        return $name;
    }

    private function SqliteFullPath(string $databaseName) :string
    {
        $databaseName = $this->guestName($databaseName);
        return sprintf("%s/%s.sqlite", database_path(), $databaseName);
    }


    /**
     * @param string $query
     */
    private function query(string $query) :void
    {
        // avoid to connect to the defaut database beacause it does not exists yet
        // so we set the name to null , run the query and reafect the created db name
        $connection = $this->getConnection();
        $schemaName = $this->getSchemaName($connection);

        config(["database.connections.mysql.database" => null]);
        DB::statement($query);
        config(["database.connections.mysql.database" => $schemaName]);
        DB::purge();
    }



}
