<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

class OrderBy implements QueryPartInterface
{
    protected array $columns;

    public function __construct(array $columns) {
        $this->columns = $columns;
    }

    public function getSql(): string {
        return 'ORDER BY ' . implode(', ', $this->columns);
    }
}