<?php

namespace src\Application\Product\Services;

use Illuminate\Support\Str;
use src\Application\Product\Commands\CreateProduct;
use src\Application\Product\DTO\ProductDTO;
use src\Domain\Product\Entities\Product;
use src\Domain\Product\RepositoryInterface;
use src\Domain\Product\ValueObjects\ProductId;

class ProductService
{
    public function __construct(
        private RepositoryInterface $repository
    ){}

    public function handleCreateProduct(CreateProduct $command): Product
    {
        $product = Product::create(
            new ProductId(Str::uuid()->toString()),
            $command->name,
            $command->price
        );
        $this->repository->store($product);

        return $product;
    }

    public function handleGetProduct(ProductId $id): ?ProductDTO
    {
        $product = $this->repository->findById($id);
        if (!$product) {
            return null;
        }
        return ProductDTO::fromEntity($product);
    }
}
