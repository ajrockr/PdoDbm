<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

interface QueryPartInterface
{
    public function getSql(): string;
}