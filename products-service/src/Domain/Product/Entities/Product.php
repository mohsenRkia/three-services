<?php

namespace Src\Domain\Product\Entities;

use Src\Domain\Product\ValueObjects\Price;
use Src\Domain\Product\ValueObjects\ProductId;
use Src\Domain\Product\ValueObjects\ProductName;

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
