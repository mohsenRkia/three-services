<?php

namespace src\Domain\Product\ValueObjects;

use InvalidArgumentException;
use src\Shared\Utils\Uuid;

class ProductId
{
    public function __construct(
        public readonly string $value
    )
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException("Product id '{$value}' is not valid.");
        }
    }
}
