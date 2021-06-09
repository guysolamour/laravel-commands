<?php


namespace Guysolamour\Command\Console\Commands\Database;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\Sqlite;
use Spatie\DbDumper\Databases\PostgreSql;

class DumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cmd:db:dump
                            {--d|driver= : Database driver. Available drivers are ['mysql', 'pgsql', 'sqlite']}
                            {--host=127.0.0.1 : Database host}
                            {--dbusername= : Database user}
                            {--dbpassword= : Database password}
                            {--dbname= : Database name}
                            {--f|filename=dump.sql : Dump filename}
                            ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database dump with different drivers.';

    /**
     * Suported database drivers
     *
     * @var string[]
     */
    private const DRIVERS = ['mysql', 'pgsql', 'sqlite'];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line("Dumping {$this->getDbName()} database....");
        $this->dumpDb();
        $this->line("{$this->getDbName()} database dumped successfully.");
    }

    private function dumpDb()
    {
        $dumper = $this->getDumper()->setDbName($this->getDbName());

        if (!$this->isSqliteDriver()) {
            $dumper
                ->setDbName($this->getDbName())
                ->setUserName($this->getUserName())
                ->setPassword($this->getPassword())
                ->setHost($this->getHost());
        }

        $dumper->dumpToFile($this->getDumpFilename());
    }

    private function isSqliteDriver(): bool
    {
        return $this->getDriver() === 'sqlite';
    }

    private function getDumper(): \Spatie\DbDumper\DbDumper
    {
        $driver = $this->getDriver();

        switch ($driver) {
            case 'mysql':
                return MySql::create();
                break;
            case 'pgsql':
                return PostgreSql::create();
                break;
            case 'sqlite':
                return Sqlite::create();
                break;
        }
    }

    private function getDriver(): ?string
    {
        $driver =  $this->option('driver') ?? config('database.default');

        if (!in_array($driver, self::DRIVERS)) {
            $this->error(sprintf("Current [%s] driver is not available. Available drivers are [%s].", $driver, join(', ', self::DRIVERS)));
        }

        return Str::lower($driver);
    }

    private function getHost(): ?string
    {
        return  $this->option('host');
    }

    private function getDumpFilename(): string
    {
        return  $this->option('filename');
    }

    private function getUserName(): ?string
    {
        return $this->option('dbusername') ?? $this->getDefaultDatabaseConnection('username');
    }

    private function getPassword(): string
    {
        return $this->option('dbpassword') ?? $this->getDefaultDatabaseConnection('password');
    }

    private function getDbName(): string
    {
        return $this->option('dbname') ?? $this->getDefaultDatabaseConnection('database');
    }

    private function getDefaultDatabaseConnection(?string $key = null, $default = null)
    {
        $config =  config("database.connections.{$this->getDriver()}", []);

        if (empty($config)) {
            return $config;
        }

        if (is_null($key)) {
            return $config;
        }

        return Arr::get($config, $key, $default);
    }
}
