<?php
namespace Guysolamour\Command\Console\Commands\Database;


use Guysolamour\Fsystem\Fsystem;


class DropDatabase extends BaseCommand
{

    protected $signature = 'cmd:db:drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop database';

    protected string $connection;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->connection = $this->getConnection();
        $this->delete();
    }



    private function delete()
    {
        $schemaName = $this->getDatabaseName($this->connection);

        $this->isSqliteDriver() ? $this->DropSqliteDatabase($schemaName) :  $this->DropMysqlDatabase($schemaName);

        $this->info("The [$schemaName] database deleted successfully with [{$this->connection}]");
    }

    /**
     * @param $schemaName
     */
    private function DropSqliteDatabase(string $path): void
    {
        $fsystem = new Fsystem();

        if ($fsystem->missing($path)){
            $this->error("{$path} database does not exist");
            exit;
        }

        $fsystem->delete($path);

    }

    /**
     * @param $schemaName
     */
    private function DropMysqlDatabase(string $schemaName): void
    {
        $this->getPDO()->exec("DROP DATABASE IF  EXISTS $schemaName");
    }
}
