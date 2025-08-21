<?php

namespace src\Domain\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\ValueObjects\ProductId;

interface RepositoryInterface
{
    public function store(Product $product): void;
    public function findById(ProductId $id): ?Product;
}
