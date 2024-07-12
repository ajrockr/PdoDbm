<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\Database\Drivers;

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseException;
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