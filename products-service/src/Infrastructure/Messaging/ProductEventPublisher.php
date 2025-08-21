<?php

namespace Messaging;

use Src\Domain\Product\Events\ProductCreated;

class ProductEventPublisher
{
    public function publishProductCreated(ProductCreated $event): void
    {
        // مثال نمایشی:
        // RabbitMQ::publish('product.created', json_encode([...]));
    }
}
