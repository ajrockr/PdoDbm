<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

use Arizzo\PdoDbm\Database\QueryResult;
use PDO;

class Update
{
    protected string $table;
    protected array $set = [];
    protected array $where = [];
    protected string $sql;
    protected PDO $pdo;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function set(array $set): self
    {
        foreach ($set as $column => $value) {
            $this->set[] = sprintf('%s = %s', $column, $value);
        }

        return $this;
    }

    public function where(array $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function getSql(): string
    {
        $set = implode(', ', $this->set);

        return "UPDATE {$this->table} ";
    }

    public function getQuery(): self
    {

    }

    public function getResult(): QueryResult
    {
        return new QueryResult($this->pdo, $this->sql);
    }
}