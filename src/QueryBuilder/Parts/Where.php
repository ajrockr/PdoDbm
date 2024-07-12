<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

class Where implements QueryPartInterface
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