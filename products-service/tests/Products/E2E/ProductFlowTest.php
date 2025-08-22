<?php

namespace Tests\Products\E2E;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use src\Application\Product\Services\ProductService;
use src\Delivery\Http\Product\Handlers\CreateProductHandler;
use src\Delivery\Http\Product\Handlers\GetProductHandler;
use src\Infrastructure\Persistence\Product\ProductReadRepository;
use src\Infrastructure\Persistence\Product\ProductWriteRepository;

class ProductFlowTest extends TestCase
{
    public function testProductLifeCycle()
    {
        $service = new ProductService(
            new ProductWriteRepository(),
            new ProductReadRepository()
        );

        $createHandler = new CreateProductHandler($service);
        $createHandler->handle('voluptatem qui et', 5886497.5);

        $productId = DB::connection('mysql_write')->table('products')->first()->id;

        $getHandler = new GetProductHandler($service);
        $dto = $getHandler->handle($productId);

        $this->assertSame('voluptatem qui et', $dto->name);
        $this->assertSame(5886497.5, $dto->price);
    }
}
