<?php

namespace src\Application\Product\DTO;


use src\Domain\Product\Entities\Product;

class ProductDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price
    ){}

    public static function fromEntity(Product $product): ProductDTO
    {
        return new self(
            $product->id->value,
            $product->name->value,
            $product->price->value
        );
    }
}
