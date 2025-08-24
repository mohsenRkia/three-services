<?php

namespace src\Application\Product\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use src\Application\Product\Commands\CreateProduct;
use src\Application\Product\DTO\ProductDTO;
use src\Application\Product\Queries\GetProduct;
use src\Domain\Product\Entities\Product;
use src\Domain\Product\Enums\EventTypeEnum;
use src\Domain\Product\Enums\OutboxStatusEnum;
use src\Domain\Product\Events\ProductCreated;
use src\Domain\Product\RepositoryReadInterface;
use src\Domain\Product\RepositoryWriteInterface;
use src\Domain\Product\ValueObjects\ProductId;
use src\Infrastructure\Persistence\Outbox\OutboxMessageModel;
use src\Infrastructure\Persistence\Product\ProductModel;

class ProductService
{
    public function __construct(
        private RepositoryWriteInterface $repositoryWrite,
        private RepositoryReadInterface $repositoryRead,
    ){}

    public function handleCreateProduct(CreateProduct $command): Product
    {
        return DB::transaction(function () use ($command) {
            $product = Product::create(
                new ProductId(Str::uuid()->toString()),
                $command->name,
                $command->price
            );
            $this->repositoryWrite->save($product);

            $event = new ProductCreated($product);

            OutboxMessageModel::create([
                'aggregate_type' => ProductModel::class,
                'aggregate_id'   => $product->id->value,
                'event_type'     => EventTypeEnum::CREATED,
                'payload'        => json_encode($event->toArray()),
                'status'         => OutboxStatusEnum::PENDING
            ]);

            return $product;
        });
    }

    public function handleGetProduct(GetProduct $query): ?ProductDTO
    {
        $product = $this->repositoryRead->findById($query->id);
        if (!$product) {
            return null;
        }
        return ProductDTO::fromEntity($product);
    }
}
