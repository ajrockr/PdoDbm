<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\Factory;

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use Ajrockr\PdoDbm\Database\DatabaseConnection;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseConfigException;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseException;
use Ajrockr\PdoDbm\QueryBuilder\QueryBuilder;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use function DI\autowire;

class DatabaseFactory
{
    private static ?Container $container = null;

    /**
     * @throws \Exception
     */
    public static function getContainer()
    {
        if (self::$container === null) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions([
                DatabaseConnection::class => autowire()->constructor(\DI\get(DatabaseConfig::class)),
                QueryBuilder::class => autowire()->constructor(\DI\get(DatabaseConnection::class))
            ]);
            self::$container = $builder->build();
        }

        return self::$container;
    }

    /**
     * @throws DatabaseConfigException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public static function createConnection(DatabaseConfig|array $config): DatabaseConnection
    {
        $databaseConfig = ($config instanceof DatabaseConfig) ? $config : new DatabaseConfig($config);

        self::checkExtensions($databaseConfig->get('driver'));

        $container = self::getContainer();
        $container->set(DatabaseConfig::class, $databaseConfig);

        return $container->get(DatabaseConnection::class);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public static function createQueryBuilder(): QueryBuilder
    {
        $container = self::getContainer();
        return $container->get(QueryBuilder::class);
    }

    private static function checkExtensions(string $driver): void
    {
        $requiredExtensions = [
            'mysql' => 'pdo_mysql',
            'pgsql' => 'pdo_pgsql',
            'sqlite' => 'pdo_sqlite'
        ];

        if (isset($requiredExtensions[$driver])) {
            if (!extension_loaded($requiredExtensions[$driver])) {
                throw new DatabaseException("Required PDO extension for $driver is note loaded.");
            }
        } else {
            throw new DatabaseException("Unsupported database driver: $driver");
        }
    }
}