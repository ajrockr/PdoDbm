<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Arizzo\PdoDbm\Database\DatabaseConfig;
use Arizzo\PdoDbm\Database\DatabaseConnection;

$config = new DatabaseConfig([
    'driver' => 'sqlite',
    'path' => 'example.sqlite']);

$databaseConnection = new DatabaseConnection($config);

$qb = $databaseConnection->getQueryBuilder();
$query = $qb->select('SQLITE_VERSION()')->getQuery()->getResult()->fetchOne();
dd($query);