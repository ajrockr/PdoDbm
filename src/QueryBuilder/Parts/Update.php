<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

class Update implements QueryPartInterface
{
    protected string $table;
    protected array $set = [];
    protected array $where = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function set(array $set): self
    {
        foreach ($set as $column => $value) {
            $this->set[] = "$column = '$value'";
        }

        return $this;
    }

    public function getSql(): string
    {
        $set = implode(', ', $this->set);

        return "UPDATE `" . $this->table . "` SET " . $set;
    }
}