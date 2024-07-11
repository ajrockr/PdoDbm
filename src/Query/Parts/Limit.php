<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class Limit
{
    protected int $limit;

    public function __construct(int $limit) {
        $this->limit = $limit;
    }

    public function getSql(): string {
        return 'LIMIT ' . $this->limit;
    }
}