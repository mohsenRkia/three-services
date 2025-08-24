<?php

namespace src\Infrastructure\Outbox;

use src\Infrastructure\Messaging\RabbitMQMessageBus;
use src\Infrastructure\Messaging\RabbitMQTestConsumer;
use src\Infrastructure\Persistence\Outbox\OutboxMessageModel;

class OutboxConsumer
{
    public function __construct(
        private RabbitMQTestConsumer $testConsumer
    ){}

    public function handle(): void
    {
            try {
                $this->testConsumer->listen();
            } catch (\Throwable $e) {
                dd($e->getMessage());
            }
    }
}
