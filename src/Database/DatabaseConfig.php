<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database;

use Arizzo\PdoDbm\Exceptions\DatabaseConfigException;
use PDO;

class DatabaseConfig
{
    private array $config;

    private array $userProvidedConfig;

    /**
     * @throws DatabaseConfigException
     */
    public function __construct(array $config)
    {
        $this->userProvidedConfig = $config;
        $this->config = $this->mergeWithDefaults($config);
        $this->validateConfig();
    }

    private function mergeWithDefaults(array $config): array
    {
        $defaultConfig = [
            'host' => '',
            'dbname' => '',
            'username' => '',
            'password' => '',
            'path' => '',
            'charset' => 'utf8mb4',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ];

        return array_replace_recursive($defaultConfig, $config);
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * @throws DatabaseConfigException
     */
    private function validateConfig(): void
    {
        if (empty($this->config['driver'])) {
            throw new DatabaseConfigException("Database driver is required");
        }

        switch ($this->config['driver']) {
            case 'sqlite':
                if ($this->config['path'] === '') {
                    throw new DatabaseConfigException("For SQLite, 'path' is required");
                }
                break;

            case 'mysql':
            case 'pgsql':
                if ($this->config['host'] === '' && $this->config['dbname'] === '' && $this->config['username'] === '' && $this->config['password'] === '') {
                    throw new DatabaseConfigException("For MySQL/PostgreSQL, valid 'host', 'dbname', 'username', and 'password' are required");
                }
                if ($this->config['host'] === '') {
                    throw new DatabaseConfigException("For Mysql/PostgreSQL, 'host' is required");
                }
                if ($this->config['dbname'] === '') {
                    throw new DatabaseConfigException("For MySQL/PostgreSQL, 'dbname' is required");
                }
                if ($this->config['username'] === '') {
                    throw new DatabaseConfigException("For MySQL/PostgreSQL, 'username' is required");
                }
                if ($this->config['password'] === '') {
                    throw new DatabaseConfigException("For MySQL/PostgreSQL, 'password' is required");
                }
                break;

            default:
                throw new DatabaseConfigException("Unsupported driver: " . $this->config['driver']);
        }
    }
}