<?php

use Src\Domain\Product\Entities\Product;
use Src\Domain\Product\RepositoryInterface;
use Src\Domain\Product\ValueObjects\Price;
use Src\Domain\Product\ValueObjects\ProductId;
use Src\Domain\Product\ValueObjects\ProductName;

class ProductRepository implements RepositoryInterface
{
    public function store(Product $product): void
    {
        ProductModel::updateOrCreate([
            ['id' => $product->id->value],
            [
                'name' => $product->name->value,
                'price' => $product->price->value
            ]
        ]);
    }

    public function findById(ProductId $id): ?Product
    {
        $product = ProductModel::find($id);
        if (!$product){
            return null;
        }
        return new Product(
            new ProductId($product->id),
            new ProductName($product->name),
            new Price($product->price),
        );
    }
}
