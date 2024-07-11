<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

class Insert implements QueryPartInterface
{
    protected string $table;
    protected array $columns = [];
    protected array $values = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function values(array $values): self
    {
        $this->values[] = $values;
        return $this;
    }

    public function getSql(): string
    {
        $columns = implode(', ', $this->columns);
        $values = [];

        foreach ($this->values as $valueSet) {
            $values[] = '(' . implode(', ', $valueSet) . ')';
        }

        $values = implode(', ', $values);

        return "INSERT INTO {$this->table} ($columns) VALUES $values";
    }
}