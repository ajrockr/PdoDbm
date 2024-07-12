<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\Database\Drivers;

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseException;
use PDO;
use RuntimeException;

class MySQLDriver implements DatabaseDriverInterface
{
    private function isExtensionLoaded(): void
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new RuntimeException('SQLite extension is not loaded');
        }
    }

    public function connect(DatabaseConfig $config): PDO
    {
        $this->isExtensionLoaded();

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config->get('host'), $config->get('dbname'), $config->get('charset'));
        $options = $config->get('options') ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            return new PDO($dsn, $config->get('username'), $config->get('password'), $options);
        } catch (DatabaseException $e) {
            throw new DatabaseException("MySQL connection failed: " . $e->getMessage());
        }
    }
}