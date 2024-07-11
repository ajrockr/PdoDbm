<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

interface QueryPartsInterface
{
    public function getSql(): string;
}