<?php

namespace src\Application\Product\Services;

use Illuminate\Support\Str;
use src\Application\Product\Commands\CreateProduct;
use src\Application\Product\DTO\ProductDTO;
use src\Application\Product\Queries\GetProduct;
use src\Domain\Product\Entities\Product;
use src\Domain\Product\RepositoryReadInterface;
use src\Domain\Product\RepositoryWriteInterface;
use src\Domain\Product\ValueObjects\ProductId;

class ProductService
{
    public function __construct(
        private RepositoryWriteInterface $repositoryWrite,
        private RepositoryReadInterface $repositoryRead,
    ){}

    public function handleCreateProduct(CreateProduct $command): Product
    {
        $product = Product::create(
            new ProductId(Str::uuid()->toString()),
            $command->name,
            $command->price
        );
        $this->repositoryWrite->save($product);

        return $product;
    }

    public function handleGetProduct(GetProduct $query): ?ProductDTO
    {
        $product = $this->repositoryRead->findById($query->id);
        if (!$product) {
            return null;
        }
        return ProductDTO::fromEntity($product);
    }
}
