<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database;

use Arizzo\PdoDbm\Database\Drivers\MySQLDriver;
use Arizzo\PdoDbm\Database\Drivers\PgSQLDriver;
use Arizzo\PdoDbm\Database\Drivers\SQLiteDriver;
use Arizzo\PdoDbm\Database\Exceptions\DatabaseException;
use Arizzo\PdoDbm\QueryBuilder\QueryBuilder;
use PDO;
class DatabaseConnection
{
    private ?PDO $pdo;
    private ?string $error = null;

    public function __construct(DatabaseConfig $config)
    {
        try {
            $driver = $this->getDriverInstance($config->get('driver'));
            $this->pdo = $driver->connect($config);
        } catch (DatabaseException $e) {
            $this->error = $e->getMessage();
        }
    }

    private function getDriverInstance($driver): PgSQLDriver|MySQLDriver|SQLiteDriver
    {
        return match ($driver) {
            'mysql' => new MySQLDriver(),
            'pgsql' => new PgsqlDriver(),
            'sqlite' => new SQLiteDriver(),
            default => throw new DatabaseException('Unsupported database driver: ' . $driver),
        };
    }

    public function getConnection(): ?DatabaseConnection
    {
        return ($this->pdo) ? $this : null;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getPDO(): ?PDO
    {
        return $this->pdo ?? null;
    }
}