<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Query\Parts;

interface QueryPartInterface
{
    public function getSql(): string;
}