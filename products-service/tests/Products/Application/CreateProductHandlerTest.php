<?php

namespace Tests\Products\Application;

use PHPUnit\Framework\TestCase;
use src\Application\Product\Commands\CreateProduct;
use src\Application\Product\Services\ProductService;
use src\Delivery\Http\Product\Handlers\CreateProductHandler;
use src\Domain\Product\ValueObjects\ProductId;

class CreateProductHandlerTest extends TestCase
{
    public function testHandlerBuildsCommandAndCallsService()
    {
        $mockService = $this->createMock(ProductService::class);
        $mockService->expects($this->once())
            ->method('handleCreateProduct')
            ->with($this->callback(function (CreateProduct $command) {
//                $this->assertInstanceOf(ProductId::class,$command->name);
                $this->assertSame('Test Product',$command->name->value);
                $this->assertSame(100.0,$command->price->value);
                return true;
            }));
        $handler = new CreateProductHandler($mockService);
        $handler->handle('Test Product', 100.0);
    }
}
