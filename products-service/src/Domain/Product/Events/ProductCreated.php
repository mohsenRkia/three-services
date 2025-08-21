<?php

namespace src\Domain\Product\Events;

use src\Domain\Product\Entities\Product;

class ProductCreated
{
    public function __construct(
        public readonly Product $product
    ){}
}
