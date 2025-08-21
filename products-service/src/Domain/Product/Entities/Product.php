<?php

namespace src\Domain\Product\Entities;

use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;

class Product
{
    public function __construct(
        public readonly ProductId $id,
        public ProductName $name,
        public Price $price
    ){}

    public static function create(ProductId $id, ProductName $name, Price $price): Product
    {
        return new self($id, $name, $price);
    }
}
