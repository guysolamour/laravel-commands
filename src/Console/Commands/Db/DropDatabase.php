<?php
namespace Guysolamour\LaravelUsefulCommands\Console\Commands\Db;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class DropDatabase extends Command
{

    use DbTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop the database with default connections';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connection = $this->getConnection();
        $this->delete($connection);
    }



    private function delete($connection)
    {
        $schemaName = $this->getSchemaName($connection);

        switch ($connection) {
            case 'sqlite':
                $this->DropSqliteDatabase($schemaName);
                break;
            case 'mysql':
                $this->DropMysqlDatabase($schemaName);
                break;
        }

        $this->info("The [$schemaName] database deleted successfully with [{$connection}]");
    }

    /**
     * @param $schemaName
     */
    private function DropSqliteDatabase($schemaName): void
    {
        $databaseName = $this->guestName($schemaName);
        $url = $this->SqliteFullPath($databaseName);
        File::delete($url);
        config(["database.connections.sqlite.database" => $databaseName]);
    }

    /**
     * @param $schemaName
     */
    private function DropMysqlDatabase($schemaName): void
    {
        $this->query("DROP DATABASE IF  EXISTS $schemaName");

    }
}
