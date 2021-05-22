<?php
namespace Guysolamour\Command\Console\Commands\Database;


use Illuminate\Support\Facades\File;


class DropDatabase extends BaseCommand
{

    protected $signature = 'cmd:db:drop
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
    protected $description = 'Drop database';

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
        $schemaName = $this->getDatabaseName($connection);

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
    }

    /**
     * @param $schemaName
     */
    private function DropMysqlDatabase($schemaName): void
    {
        $this->getPDO()->exec("DROP DATABASE IF  EXISTS $schemaName");
    }
}
