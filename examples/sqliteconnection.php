<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use Ajrockr\PdoDbm\Factory\DatabaseFactory;

try {
    $config = new DatabaseConfig([
        'driver' => 'sqlite',
        'path' => 'example.sqlite']);

    $connection = DatabaseFactory::createConnection($config);

    $queryBuilder = DatabaseFactory::createQueryBuilder();
    $query = $queryBuilder->select('SQLITE_VERSION()')->getQuery()->getResult()->fetchOne();
    dd($query);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
