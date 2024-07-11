<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\QueryBuilder\Parts;

interface QueryPartsInterface
{
    public function getSql(): string;
}