<?php

namespace src\Infrastructure\Persistence\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\RepositoryReadInterface;
use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;
use src\Infrastructure\Persistence\Product\ProductModel;

class ProductReadRepository implements RepositoryReadInterface
{
    private string $connection;

    public function __construct()
    {
        $this->connection = env('DB_READ_CONNECTION');
    }
    public function findById(ProductId $id): ?Product
    {
        $product = ProductModel::on($this->connection)
            ->find($id->value);
        if (!$product) {
            return null;
        }
        return new Product(
            new ProductId($product->id),
            new ProductName($product->name),
            new Price($product->price),
        );
    }
}
