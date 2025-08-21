<?php

namespace Services;

use CreateProduct;
use DTO\ProductDTO;
use Queries\GetProduct;
use Src\Domain\Product\Entities\Product;
use Src\Domain\Product\RepositoryInterface;

class ProductService
{
    public function __construct(
        private RepositoryInterface $repository
    ){}

    public function handleCreateProduct(CreateProduct $command): void
    {
        $product = Product::create(
            $command->id,
            $command->name,
            $command->price
        );
        $this->repository->store($product);
    }

    public function handleGetProduct(GetProduct $query): ?ProductDTO
    {
        $product = $this->repository->findById($query->id);
        if (!$product) {
            return null;
        }
        return ProductDTO::fromEntity($product);
    }
}
