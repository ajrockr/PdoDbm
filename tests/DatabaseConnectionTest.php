<?php

namespace Arizzo\PdoDbm\Tests;

use Arizzo\PdoDbm\Database\DatabaseConfig;
use Arizzo\PdoDbm\Database\DatabaseConnection;
use Arizzo\PdoDbm\Exceptions\DatabaseConfigException;
use InvalidArgumentException;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    /**
     * @throws DatabaseConfigException
     */
    public function testSQLiteConnection() {
        $this->runDatabaseTest('sqlite', ':memory:');
    }

    /**
     * @throws DatabaseConfigException
     */
//    public function testMySQLConnection() {
//        // Adjust these values to match your MySQL server configuration
//        $this->runDatabaseTest('mysql', '127.0.0.1', 'testdb', 'root', 'password');
//    }

    /**
     * @throws DatabaseConfigException
     */
//    public function testPgSQLConnection() {
//        // Adjust these values to match your PostgreSQL server configuration
//        $this->runDatabaseTest('pgsql', '127.0.0.1', 'testdb', 'postgres', 'password');
//    }

    /**
     * @throws DatabaseConfigException
     */
    private function runDatabaseTest($driver, $hostOrPath, $dbname = null, $username = null, $password = null): void
    {
        // Set up database configuration based on driver
        $config = match ($driver) {
            'sqlite' => new DatabaseConfig([
                'driver' => 'sqlite',
                'path' => $hostOrPath,
            ]),
            'mysql' => new DatabaseConfig([
                'driver' => 'mysql',
                'host' => $hostOrPath,
                'dbname' => $dbname,
                'username' => $username,
                'password' => $password,
            ]),
            'pgsql' => new DatabaseConfig([
                'driver' => 'pgsql',
                'host' => $hostOrPath,
                'dbname' => $dbname,
                'username' => $username,
                'password' => $password,
            ]),
            default => throw new InvalidArgumentException("Unsupported database driver: $driver"),
        };

        // Create a DatabaseConnection instance
        $connection = new DatabaseConnection($config);

        // Test connection
        $pdo = $connection->getConnection();
        $this->assertInstanceOf(PDO::class, $pdo);

        // Perform a basic query to test the connection
        $tableName = 'test_table';
        $pdo->exec("CREATE TABLE $tableName (id SERIAL PRIMARY KEY, name VARCHAR(255))");

        // Insert data
        $insertStmt = $pdo->prepare("INSERT INTO $tableName (name) VALUES (:name)");
        $insertStmt->execute([':name' => 'John Doe']);

        // Retrieve data
        $selectStmt = $pdo->query("SELECT * FROM $tableName");
        $result = $selectStmt->fetch(PDO::FETCH_ASSOC);

        // Assert that data was inserted and retrieved correctly
        $this->assertEquals('John Doe', $result['name']);
    }
}
