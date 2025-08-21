<?php

namespace src\Application\Product\Commands;
use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;

class CreateProduct
{
    public function __construct(
        public readonly ProductName $name,
        public readonly Price $price
    )
    {

    }
}
