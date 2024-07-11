<?php declare(strict_types=1);

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        DatabaseConnection::class => function () {
            $config = require __DIR __ . '/config.php';
            return new DatabaseConnection($config);
        },
        QueryBuilder::class => function (DatabaseConnection $dbConnection) {
            return new QueryBuilder($dbConnection->getConnection());
        }
    ]);
};