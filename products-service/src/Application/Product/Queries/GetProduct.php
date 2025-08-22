<?php

namespace src\Application\Product\Queries;

use src\Domain\Product\ValueObjects\ProductId;

class GetProduct
{
    public ProductId $id;
    public function __construct(ProductId $id)
    {
        $this->id = $id;
    }
}
