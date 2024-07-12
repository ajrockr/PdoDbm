<?php

namespace Arizzo\PdoDbm\Tests\QueryBuilder;

use Arizzo\PdoDbm\Database\DatabaseConnection;
use Arizzo\PdoDbm\Database\Exceptions\DatabaseConfigException;
use Arizzo\PdoDbm\Database\Result\ExecutionResult;
use Arizzo\PdoDbm\Database\Result\QueryResult;
use Arizzo\PdoDbm\Factory\DatabaseFactory;
use Arizzo\PdoDbm\QueryBuilder\QueryBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private DatabaseConnection $connection;
    private QueryBuilder $queryBuilder;

    /**
     * @throws DependencyException
     * @throws DatabaseConfigException
     * @throws NotFoundException
     */
    protected function setUp(): void {
        $config = [
            'driver' => 'sqlite',
            'path' => ':memory:',
        ];

        $this->connection = DatabaseFactory::createConnection($config)->getConnection();
        $this->queryBuilder = new QueryBuilder($this->connection);

        // Create a table for testing
        $pdo = $this->connection->getConnection()->getPDO();
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
    }

    public function testSelectQuery() {
        $pdo = $this->connection->getConnection()->getPDO();
        $pdo->exec("INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')");

        $this->queryBuilder->select(['name', 'email'])
            ->from('users')
            ->where(['id = 1']);

        $queryResult = $this->queryBuilder->getQuery()->getResult();
        $this->assertInstanceOf(QueryResult::class, $queryResult);

        $results = $queryResult->fetchAll();
        $this->assertCount(1, $results);
        $this->assertEquals('John Doe', $results[0]['name']);
        $this->assertEquals('john@example.com', $results[0]['email']);
    }

    public function testInsertQuery() {
        $query = $this->queryBuilder->insert('users')
            ->columns(['name', 'email'])
            ->values(['Jane Doe', 'jane@example.com']);

        $executionResult = $query->execute();
        $this->assertInstanceOf(ExecutionResult::class, $executionResult);

        $this->assertEquals(1, $executionResult->getRowCount());
        $this->assertNotNull($executionResult->getLastInsertId());

        // Verify the data was inserted
        $pdo = $this->connection->getConnection()->getPDO();
        $stmt = $pdo->query("SELECT * FROM users WHERE id = " . $executionResult->getLastInsertId());
        $result = $stmt->fetch();
        $this->assertEquals('Jane Doe', $result['name']);
        $this->assertEquals('jane@example.com', $result['email']);
    }

    public function testUpdateQuery() {
        $pdo = $this->connection->getConnection()->getPDO();
        $pdo->exec("INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')");

        $query = $this->queryBuilder->update('users')
            ->set(['email' => 'john.doe@example.com'])
            ->where(['id = 1']);

//        dd($query->getQuery()->getSql());

        $executionResult = $query->execute();
        $this->assertInstanceOf(ExecutionResult::class, $executionResult);

        $this->assertEquals(1, $executionResult->getRowCount());

        // Verify the data was updated
        $stmt = $pdo->query("SELECT * FROM users WHERE id = 1");
        $result = $stmt->fetch();
        $this->assertEquals('john.doe@example.com', $result['email']);
    }

    public function testDeleteQuery() {
        $pdo = $this->connection->getConnection()->getPDO();
        $pdo->exec("INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')");

        $query = $this->queryBuilder->delete('users')
            ->where(['id = 1']);

        $executionResult = $query->execute();
        $this->assertInstanceOf(ExecutionResult::class, $executionResult);

        $this->assertEquals(1, $executionResult->getRowCount());

        // Verify the data was deleted
        $stmt = $pdo->query("SELECT * FROM users WHERE id = 1");
        $result = $stmt->fetch();
        $this->assertFalse($result);
    }
}
