<?php

namespace Queries;

use Src\Domain\Product\ValueObjects\ProductId;

class GetProduct
{
    public function __construct(
        public readonly ProductId $id
    ){}
}
