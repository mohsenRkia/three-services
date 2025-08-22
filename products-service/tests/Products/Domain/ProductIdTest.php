<?php

namespace Tests\Products\Domain;
use PHPUnit\Framework\TestCase;
use src\Domain\Product\ValueObjects\ProductId;

class ProductIdTest extends TestCase
{
    public function testAcceptValidUuid()
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $vo = new ProductId($uuid);
        $this->assertSame($uuid,$vo->value);
    }

    public function testRejectsInvalidUuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ProductId('not-uuid');
    }
}
