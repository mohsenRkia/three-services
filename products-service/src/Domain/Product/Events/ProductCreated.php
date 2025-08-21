<?php

namespace Src\Domain\Product\Events;

use Src\Domain\Product\Entities\Product;

class ProductCreated
{
    public function __construct(
        public readonly Product $product
    ){}
}
