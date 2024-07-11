<?php

namespace Arizzo\PdoDbm\Database\Result;

class ExecutionResult
{
    private int $rowCount;
    private ?string $lastInsertId;

    public function __construct(int $rowCount, ?string $lastInsertId = null)
    {
        $this->rowCount = $rowCount;
        $this->lastInsertId = $lastInsertId;
    }

    /**
     * @return string|null
     */
    public function getLastInsertId(): ?string
    {
        return $this->lastInsertId;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}