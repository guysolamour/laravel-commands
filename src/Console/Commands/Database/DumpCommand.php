<?php


namespace Guysolamour\Command\Console\Commands\Database;


use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\Sqlite;

// class DumpCommand extends Command
class DumpCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cmd:db:dump {filename=dump.sql}";


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create current database dump with different drivers.';



    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line("Dumping {$this->getDatabaseName()} database....");
        $this->dumpDb();
        $this->line("{$this->getDatabaseName()} database dumped successfully.");
    }


    private function dumpDb()
    {
        $dumper = $this->getDumper()->setDbName($this->getDatabaseName());

        if ($this->isNotSqliteDriver()) {
            $dumper
                ->setDbName($this->getDatabaseName())
                ->setUserName($this->getDatabaseCredentials('username'))
                ->setPassword($this->getDatabaseCredentials('password'))
                ->setHost($this->getDatabaseCredentials('host'));
        }

        $dumper->dumpToFile($this->argument('filename'));
    }


    private function getDumper(): \Spatie\DbDumper\DbDumper
    {
        return $this->isSqliteDriver() ? Sqlite::create() : MySql::create();
    }
}
