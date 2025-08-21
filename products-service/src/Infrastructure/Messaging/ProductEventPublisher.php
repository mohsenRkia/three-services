<?php

namespace Messaging;

use src\Domain\Product\Events\ProductCreated;

class ProductEventPublisher
{
    public function publishProductCreated(ProductCreated $event): void
    {
        // مثال نمایشی:
        // RabbitMQ::publish('product.created', json_encode([...]));
    }
}
