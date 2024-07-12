<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\QueryBuilder\Parts;

interface QueryPartsInterface
{
    public function getSql(): string;
}