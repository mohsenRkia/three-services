<?php

namespace src\Infrastructure\Persistence\Product;

use src\Domain\Product\Entities\Product;
use src\Domain\Product\RepositoryWriteInterface;

class ProductWriteRepository implements RepositoryWriteInterface
{
    private string $connection;

    public function __construct()
    {
        $this->connection = env('DB_WRITE_CONNECTION','mysql_write');
    }
    public function save(Product $product): void
    {
        ProductModel::on($this->connection)
            ->updateOrCreate(['id' => $product->id->value],
            [
                'name' => $product->name->value,
                'price' => $product->price->value
            ]
        );
    }
}
