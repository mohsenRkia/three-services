<?php

use Src\Domain\Product\ValueObjects\Price;
use Src\Domain\Product\ValueObjects\ProductId;
use Src\Domain\Product\ValueObjects\ProductName;

class CreateProduct
{
    public function __construct(
        public readonly ProductId $id,
        public readonly ProductName $name,
        public readonly Price $price
    )
    {

    }
}
