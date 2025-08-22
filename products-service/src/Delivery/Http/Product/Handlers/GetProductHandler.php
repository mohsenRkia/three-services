<?php

namespace src\Delivery\Http\Product\Handlers;

use src\Application\Product\DTO\ProductDTO;
use src\Application\Product\Queries\GetProduct;
use src\Application\Product\Services\ProductService;
use src\Domain\Product\ValueObjects\ProductId;

class GetProductHandler
{
    public function __construct(
        private ProductService $productService
    ){}

    public function handle(string $id): ProductDTO
    {
        $query = new GetProduct(new ProductId($id));
        return $this->productService->handleGetProduct($query);
    }
}
