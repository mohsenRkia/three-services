<?php

namespace src\Domain\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\ValueObjects\ProductId;

interface RepositoryReadInterface
{
    public function findById(ProductId $id): ?Product;
}
