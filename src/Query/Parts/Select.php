<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

use Arizzo\PdoDbm\Database\QueryResult;
use PDO;

class Select implements QueryPartsInterface
{
    protected PDO $pdo;
    protected string $sql;
    protected array $columns;

    protected array $queryParts = [];

    public function __construct(PDO $pdo, array $columns)
    {
        $this->pdo = $pdo;
        $this->columns = $columns;
    }

    public function from(string $table): self
    {
        $this->queryParts['from'] = new From($table);
        return $this;
    }

    public function where(array $conditions): self
    {
        $this->queryParts['where'] = new Where($conditions);
        return $this;
    }

    public function orderBy(array $columns): self
    {
        $this->queryParts['orderBy'] = new OrderBy($columns);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->queryParts['limit'] = new Limit($limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->queryParts['offset'] = new Offset($offset);
        return $this;
    }

    public function groupBy(array $columns): self
    {
        $this->queryParts['groupBy'] = new GroupBy($columns);
        return $this;
    }

    public function getQuery(): self
    {
        $sql = [];

        foreach (['from', 'where', 'orderBy', 'limit', 'offset', 'groupBy', 'limit', 'offset', 'orderBy'] as $part) {
            if (isset($this->queryParts[$part])) {
                $sql[] = $this->queryParts[$part]->getSql();
            }
        }

        $this->sql = 'SELECT ' . implode(', ', $this->columns) . implode(' ', $sql);
        return $this;
    }

    public function getSql(): string
    {
        return 'SELECT ' . implode(', ', $this->columns);
    }

    public function getResult(): QueryResult
    {
        return new QueryResult($this->pdo, $this->sql);
    }
}