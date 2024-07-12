<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\Database\Result;

use PDOStatement;

class QueryResult
{
    private PDOStatement $statement;

    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }

    public function fetchOne(): array|bool
    {
        return $this->statement->fetch();
    }
}