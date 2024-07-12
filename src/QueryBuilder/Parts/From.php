<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

class From implements QueryPartInterface
{
    protected string $table;

    public function __construct(string $table) {
        $this->table = $table;
    }

    public function getSql(): string {
        return 'FROM ' . $this->table;
    }
}