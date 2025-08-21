<?php

namespace Src\Domain\Product\ValueObjects;

class ProductName
{
    public function __construct(
        public readonly string $value,
    )
    {
        if (strlen($value) < 3){
            throw new \InvalidArgumentException("Product name '{$value}' is too short.");
        }
    }
}
