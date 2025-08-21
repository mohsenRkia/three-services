<?php

namespace Src\Domain\Product\ValueObjects;

use http\Exception\InvalidArgumentException;
use Src\Shared\Utils\Uuid;

class ProductId
{
    public function __construct(
        public readonly string $value
    )
    {
        if (Uuid::isValid($value)) {
            throw new InvalidArgumentException("Product id '{$value}' already exists.");
        }
    }
}
