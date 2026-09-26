<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use Throwable;

#[Signature('app:initialize')]
#[Description('Cria o banco de dados, se necessário, e executa as migrações')]
class InitializeApplication extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connectionName = config('database.default');
        $connection = config("database.connections.{$connectionName}");

        if (! is_array($connection)) {
            $this->error("A conexão [{$connectionName}] não está configurada.");

            return self::FAILURE;
        }

        try {
            $this->ensureDatabaseExists($connectionName, $connection);
            $this->info('Banco de dados disponível.');
        } catch (Throwable $exception) {
            $this->error('Não foi possível criar ou conectar ao banco de dados.');
            $this->line($exception->getMessage());

            return self::FAILURE;
        }

        return $this->call('migrate', ['--force' => true]);
    }

    /**
     * @param  array<string, mixed>  $connection
     */
    private function ensureDatabaseExists(string $connectionName, array $connection): void
    {
        $driver = $connection['driver'] ?? null;

        if ($driver === 'sqlite') {
            $database = (string) ($connection['database'] ?? '');
            if ($database !== ':memory:' && ! file_exists($database)) {
                $directory = dirname($database);
                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                touch($database);
            }
            DB::connection($connectionName)->getPdo();

            return;
        }

        try {
            DB::connection($connectionName)->getPdo();

            return;
        } catch (Throwable) {
            if (! in_array($driver, ['mysql', 'mariadb'], true)) {
                throw new \RuntimeException("Criação automática não suportada para o driver [{$driver}].");
            }
        }

        $database = (string) ($connection['database'] ?? '');
        if ($database === '' || ! preg_match('/^[a-zA-Z0-9_$-]+$/', $database)) {
            throw new \RuntimeException('O nome do banco de dados é inválido ou não foi informado.');
        }

        $host = $connection['host'] ?? '127.0.0.1';
        $port = $connection['port'] ?? 3306;
        $charset = $connection['charset'] ?? 'utf8mb4';
        $dsn = "mysql:host={$host};port={$port};charset={$charset}";
        $pdo = new PDO($dsn, $connection['username'] ?? null, $connection['password'] ?? null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $collation = $connection['collation'] ?? 'utf8mb4_unicode_ci';
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET {$charset} COLLATE {$collation}");

        DB::purge($connectionName);
        DB::connection($connectionName)->getPdo();
    }
}
