<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class Delete implements QueryPartInterface
{
    protected string $table;
    protected array $where = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function where(array $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function getSql(): string
    {
        $where = implode(' AND ', $this->where);
        return "DELETE FROM {$this->table} WHERE $where";
    }
}