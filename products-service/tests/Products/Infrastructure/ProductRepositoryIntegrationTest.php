<?php

namespace Tests\Products\Infrastructure;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use src\Domain\Product\Entities\Product;
use src\Domain\Product\ValueObjects\Price;
use src\Domain\Product\ValueObjects\ProductId;
use src\Domain\Product\ValueObjects\ProductName;
use src\Infrastructure\Persistence\Product\ProductReadRepository;
use src\Infrastructure\Persistence\Product\ProductWriteRepository;

class ProductRepositoryIntegrationTest extends TestCase
{
    public function testWriteAndReadProduct()
    {
        $id = new ProductId(Str::uuid()->toString());
        $product = new Product($id, new ProductName('Test Product'), new Price(55.5));

        $writeRepo = new ProductWriteRepository();
        $readRepo = new ProductReadRepository();

        $writeRepo->save($product);

        $fetched = $readRepo->findById($id);

        $this->assertNotNull($fetched);
        $this->assertSame('Test Product', $fetched->name->value);
        $this->assertSame(55.5, $fetched->price->value);
    }
}
