<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

class Limit implements QueryPartInterface
{
    protected int $limit;

    public function __construct(int $limit) {
        $this->limit = $limit;
    }

    public function getSql(): string {
        return 'LIMIT ' . $this->limit;
    }
}