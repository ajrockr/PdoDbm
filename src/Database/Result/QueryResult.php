<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database\Result;

use Arizzo\PdoDbm\Database\Exceptions\DatabaseException;
use PDO;
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