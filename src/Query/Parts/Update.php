<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

use Arizzo\PdoDbm\Database\QueryResult;

class Update implements QueryPartInterface
{
    protected string $table;
    protected array $set = [];
    protected array $where = [];
    protected string $sql;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function set(array $set): self
    {
        foreach ($set as $column => $value) {
            $this->set[] = "$column = $value";
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
        $where = implode(' AND ', $this->where);

        return "UPDATE {$this->table} SET $set WHERE $where";
    }
}