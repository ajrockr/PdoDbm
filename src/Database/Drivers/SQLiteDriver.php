<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database\Drivers;

use Arizzo\PdoDbm\Database\DatabaseConfig;
use Arizzo\PdoDbm\Database\DatabaseDriverInterface;
use Arizzo\PdoDbm\Exceptions\DatabaseException;
use RuntimeException;
use PDO;

class SQLiteDriver implements DatabaseDriverInterface
{
    private function isExtensionLoaded(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            throw new RuntimeException('SQLite extension is not loaded');
        }
    }

    public function connect(DatabaseConfig $config): PDO
    {
        $this->isExtensionLoaded();

        $dsn = 'sqlite:' . $config->get('path');
        $options = $config->get('options') ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            return new PDO($dsn, options: $options);
        } catch (\PDOException $e) {
            throw new DatabaseException(sprintf('%s connection failed: %s', $config->get('driver'), $e->getMessage()));
        }
    }
}