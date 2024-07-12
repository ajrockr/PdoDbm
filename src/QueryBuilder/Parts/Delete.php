<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

class Delete implements QueryPartInterface
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function getSql(): string
    {
        return "DELETE FROM " . $this->table;
    }
}