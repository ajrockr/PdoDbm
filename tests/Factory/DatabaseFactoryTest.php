<?php

namespace Ajrockr\PdoDbm\Tests\Factory;

use Ajrockr\PdoDbm\Database\DatabaseConnection;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseConfigException;
use Ajrockr\PdoDbm\Factory\DatabaseFactory;
use Ajrockr\PdoDbm\QueryBuilder\QueryBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseFactoryTest extends TestCase
{
    /**
     * @throws DependencyException
     * @throws DatabaseConfigException
     * @throws NotFoundException
     */
    public function testCreateSqliteConnection() {
        $config = [
            'driver' => 'sqlite',
            'path' => ':memory:',
        ];

        $connection = DatabaseFactory::createConnection($config);

        $this->assertInstanceOf(DatabaseConnection::class, $connection);
        $this->assertInstanceOf(PDO::class, $connection->getConnection()->getPDO());
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCreateQueryBuilder() {
        $queryBuilder = DatabaseFactory::createQueryBuilder();

        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }

    /**
     * @throws DatabaseConfigException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testMissingPdoExtension() {
        $config = [
            'driver' => 'unsupported_driver',
            'dbname' => ':memory:',
        ];

        $this->expectException(DatabaseConfigException::class);
        DatabaseFactory::createConnection($config);
    }
}
