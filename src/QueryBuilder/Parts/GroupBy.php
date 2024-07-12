<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

class GroupBy implements QueryPartInterface
{
    protected array $columns;

    public function __construct(array $columns) {
        $this->columns = $columns;
    }

    public function getSql(): string {
        return 'GROUP BY ' . implode(', ', $this->columns);
    }
}