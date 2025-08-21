<?php

namespace src\Delivery\Http\Product\Handlers;

use src\Application\Product\Commands\CreateProduct;
use src\Application\Product\Services\ProductService;
use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;

class CreateProductHandler
{
    public function __construct(
        private ProductService $productService
    ){}

    public function handle(string $name, float $price)
    {
        $command = new CreateProduct(
            new ProductName($name),
            new Price($price)
        );
        return $this->productService->handleCreateProduct($command);

    }
}
