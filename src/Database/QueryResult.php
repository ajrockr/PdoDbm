<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database;

use Arizzo\PdoDbm\Exceptions\DatabaseException;
use PDO;
use PDOStatement;
class QueryResult
{
    private PDOStatement $statement;
    private PDO $pdo;

    public function __construct(PDO $pdo, string $query)
    {
        $this->pdo = $pdo;
        $this->prepareAndExecute($query);
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }

    public function fetchOne(): array|bool
    {
        return $this->statement->fetch();
    }

    private function prepareAndExecute(string $query, array $params = []): void
    {
        try {
            $this->statement = $this->pdo->prepare($query);
            $this->statement->execute($params);
        } catch (DatabaseException $e) {
            throw new DatabaseException("Query execution failed: " . $e->getMessage());
        }
    }
}