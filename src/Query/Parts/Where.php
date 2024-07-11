<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

class Where
{
    protected array $conditions;

    public function __construct(array $conditions) {
        $this->conditions = $conditions;
    }

    public function getSql(): string {
        if (empty($this->conditions)) {
            return '';
        }
        return 'WHERE ' . implode(' AND ', $this->conditions);
    }
}