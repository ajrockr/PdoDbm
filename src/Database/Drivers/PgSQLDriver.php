<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database\Drivers;

use Arizzo\PdoDbm\Database\DatabaseConfig;
use Arizzo\PdoDbm\Database\DatabaseDriverInterface;
use Arizzo\PdoDbm\Exceptions\DatabaseException;
use PDO;
use RuntimeException;

class PgSQLDriver implements DatabaseDriverInterface
{
    private function isExtensionLoaded(): void
    {
        if (!extension_loaded('pdo_pgsql')) {
            throw new RuntimeException('SQLite extension is not loaded');
        }
    }

    public function connect(DatabaseConfig $config): PDO
    {
        $this->isExtensionLoaded();

        $dsn = sprintf('pgsql:host=%s;dbname=%s', $config->get('host'), $config->get('dbname'));
        $options = $config->get('options') ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            return new PDO($dsn, $config->get('username'), $config->get('password'), $options);
        } catch (DatabaseException $e) {
            throw new DatabaseException('MySQL connection failed: ' . $e->getMessage());
        }
    }
}