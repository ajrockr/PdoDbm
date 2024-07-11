<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query;

use Arizzo\PdoDbm\Database\QueryResult;
use Arizzo\PdoDbm\Query\Parts\From;
use Arizzo\PdoDbm\Query\Parts\GroupBy;
use Arizzo\PdoDbm\Query\Parts\Limit;
use Arizzo\PdoDbm\Query\Parts\Offset;
use Arizzo\PdoDbm\Query\Parts\OrderBy;
use Arizzo\PdoDbm\Query\Parts\Select;
use Arizzo\PdoDbm\Query\Parts\Update;
use Arizzo\PdoDbm\Query\Parts\Where;
use PDO;

class QueryBuilder
{
    protected array $queryParts = [];

    protected PDO $pdo;
    protected string $sql;
    protected array $params = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    public function bind($param, $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    public function executeQuery(string $query, array $params = []): QueryResult
    {
        return new QueryResult($this->pdo, $query, $params);
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getResult(): QueryResult
    {
        return new QueryResult($this->pdo, $this->sql, $this->params);
    }

    public function select(array|string $columns = '*'): Select
    {
        return $this->queryParts['select'] = is_array($columns) ? new Select($this->pdo, $columns) : new Select($this->pdo, [$columns]);
    }

    public function update(string $table, array $set = [], array $where = []): Update
    {
        $this->queryParts['update'] = new Update($table);
        return $this->queryParts['update'];
    }
}