<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class GroupBy
{
    protected array $columns;

    public function __construct(array $columns) {
        $this->columns = $columns;
    }

    public function getSql(): string {
        return 'GROUP BY ' . implode(', ', $this->columns);
    }
}