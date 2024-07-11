<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class From
{
    protected string $table;

    public function __construct(string $table) {
        $this->table = $table;
    }

    public function getSql(): string {
        return 'FROM ' . $this->table;
    }
}