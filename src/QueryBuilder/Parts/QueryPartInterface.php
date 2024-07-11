<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

interface QueryPartInterface
{
    public function getSql(): string;
}