<?php

namespace src\Domain\Product\Events;

use src\Domain\Product\Entities\Product;

class ProductCreated
{
    public function __construct(
        public readonly Product $product
    ){}

    public function toArray(): array
    {
        return [
            'product_id' => $this->product->id->value,
            'name' => $this->product->name->value,
            'price' => $this->product->price->value,
        ];
    }
}
