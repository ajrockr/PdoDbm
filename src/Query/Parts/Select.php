<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class Select implements QueryPartInterface
{
    protected array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function getSql(): string
    {
        return 'SELECT ' . implode(', ', $this->columns);
    }
}