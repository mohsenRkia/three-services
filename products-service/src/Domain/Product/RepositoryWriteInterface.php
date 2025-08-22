<?php

namespace src\Domain\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\ValueObjects\ProductId;

interface RepositoryWriteInterface
{
    public function save(Product $product): void;
}
