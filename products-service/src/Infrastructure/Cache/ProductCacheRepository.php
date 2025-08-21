<?php

use Illuminate\Support\Facades\Cache;
use Src\Domain\Product\Entities\Product;
use Src\Domain\Product\ValueObjects\Price;
use Src\Domain\Product\ValueObjects\ProductId;
use Src\Domain\Product\ValueObjects\ProductName;

class ProductCacheRepository
{
    private string $prefix = 'product:';

    public function put(Product $product, int $ttlSeconds = 3600): void
    {
        $key = $this->generateKey($product->id->value);

        Cache::put($key, [
            'id' => $product->id->value,
            'name' => $product->name->value,
            'price' => $product->price->value
        ], $ttlSeconds);
    }

    public function get(ProductId $id): ?Product
    {
        $key = $this->generateKey($id);

        $data = Cache::get($key);

        if (!$data) {
            return null;
        }

        return new Product(
            new ProductId($data['id']),
            new ProductName($data['name']),
            new Price($data['price']),
        );
    }

    private function generateKey($id)
    {
        return $this->prefix . $id->value;
    }
}
