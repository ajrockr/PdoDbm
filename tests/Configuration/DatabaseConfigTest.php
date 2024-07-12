<?php

namespace Ajrockr\PdoDbm\Tests\Configuration;

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use Ajrockr\PdoDbm\Database\Exceptions\DatabaseConfigException;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseConfigTest extends TestCase
{
    public function testValidSQLiteConfig() {
        $configArray = [
            'driver' => 'sqlite',
            'path' => ':memory:',
        ];

        $config = new DatabaseConfig($configArray);
        $this->assertEquals(':memory:', $config->get('path'));
        $this->assertEquals('sqlite', $config->get('driver'));
        $this->assertEquals('', $config->get('host')); // default
    }

    public function testValidMySQLConfig() {
        $configArray = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'dbname' => 'testdb',
            'username' => 'root',
            'password' => 'password',
        ];

        $config = new DatabaseConfig($configArray);
        $this->assertEquals('127.0.0.1', $config->get('host'));
        $this->assertEquals('testdb', $config->get('dbname'));
        $this->assertEquals('root', $config->get('username'));
        $this->assertEquals('password', $config->get('password'));
    }

    public function testValidPgSQLConfig() {
        $configArray = [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'dbname' => 'testdb',
            'username' => 'root',
            'password' => 'password',
        ];

        $config = new DatabaseConfig($configArray);
        $this->assertEquals('127.0.0.1', $config->get('host'));
        $this->assertEquals('testdb', $config->get('dbname'));
        $this->assertEquals('root', $config->get('username'));
        $this->assertEquals('password', $config->get('password'));
    }

    public function testInvalidSQLiteConfig() {
        $this->expectException(DatabaseConfigException::class);
        $this->expectExceptionMessage("For SQLite, 'path' is required");

        $configArray = [
            'driver' => 'sqlite',
        ];

        new DatabaseConfig($configArray);
    }

    public function testInvalidMySQLConfig() {
        $this->expectException(DatabaseConfigException::class);
        $this->expectExceptionMessage("For MySQL/PostgreSQL, 'dbname' is required");

        $configArray = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => 'password',
        ];

        new DatabaseConfig($configArray);
    }

    public function testInvalidPgSQLConfig() {
        $this->expectException(DatabaseConfigException::class);
        $this->expectExceptionMessage("For Mysql/PostgreSQL, 'host' is required");

        $configArray = [
            'driver' => 'pgsql',
            'dbname' => 'testdb',
            'username' => 'root',
            'password' => 'password',
        ];

        new DatabaseConfig($configArray);
    }

    public function testUnsupportedDriver() {
        $this->expectException(DatabaseConfigException::class);
        $this->expectExceptionMessage("Unsupported driver: unknown");

        $configArray = [
            'driver' => 'unknown',
        ];

        new DatabaseConfig($configArray);
    }

    public function testDefaultOptions() {
        $configArray = [
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'testdb',
            'username' => 'root',
            'password' => 'password',
        ];

        $config = new DatabaseConfig($configArray);
        $options = $config->get('options');

        $this->assertArrayHasKey(PDO::ATTR_ERRMODE, $options);
        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $options[PDO::ATTR_ERRMODE]);
        $this->assertArrayHasKey(PDO::ATTR_DEFAULT_FETCH_MODE, $options);
        $this->assertEquals(PDO::FETCH_ASSOC, $options[PDO::ATTR_DEFAULT_FETCH_MODE]);
        $this->assertArrayHasKey(PDO::ATTR_EMULATE_PREPARES, $options);
        $this->assertFalse($options[PDO::ATTR_EMULATE_PREPARES]);
    }
}
