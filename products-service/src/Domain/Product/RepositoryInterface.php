<?php

namespace Src\Domain\Product;

use Src\Domain\Product\Entities\Product;
use Src\Domain\Product\ValueObjects\ProductId;

interface RepositoryInterface
{
    public function store(Product $product): void;
    public function findById(ProductId $id): ?Product;
}
