<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

class Offset implements QueryPartInterface
{
    protected int $offset;

    public function __construct(int $offset) {
        $this->offset = $offset;
    }

    public function getSql(): string {
        return 'OFFSET ' . $this->offset;
    }
}