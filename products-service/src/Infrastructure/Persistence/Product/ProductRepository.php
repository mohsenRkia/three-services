<?php

namespace src\Infrastructure\Persistence\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\RepositoryInterface;
use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;
use src\Infrastructure\Persistence\Product\ProductModel;

class ProductRepository implements RepositoryInterface
{
    public function store(Product $product): void
    {
        ProductModel::updateOrCreate(
            ['id' => $product->id->value],
            [
                'name' => $product->name->value,
                'price' => $product->price->value
            ]
        );
    }

    public function findById(ProductId $id): ?Product
    {
        $product = ProductModel::find($id->value);
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
