<?php

namespace src\Application\Product\Queries;

use src\Domain\Product\ValueObjects\ProductId;

class GetProduct
{
    public function __construct(
        public readonly ProductId $id
    ){}
}
