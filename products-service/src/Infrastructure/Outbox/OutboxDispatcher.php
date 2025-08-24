<?php

namespace src\Infrastructure\Outbox;

use src\Domain\Product\Enums\OutboxStatusEnum;
use src\Infrastructure\Messaging\RabbitMQMessageBus;
use src\Infrastructure\Persistence\Outbox\OutboxMessageModel;

class OutboxDispatcher
{
    public function __construct(
        private RabbitMQMessageBus $messageBus
    ){}

    public function handle(): void
    {

        $messages = OutboxMessageModel::where('status', 'pending')->limit(50)->get();

        foreach ($messages as $message) {
            try {
                $this->messageBus->publish(
                    $message->event_type,
                    json_decode($message->payload, true)
                );

                $message->update([
                    'status' => OutboxStatusEnum::PROCESSED,
                    'processed_at' => now(),
                ]);
            } catch (\Throwable $e) {
                $message->update([
                    'status' => OutboxStatusEnum::FAILED,
                    'processed_at' => now(),
                ]);
            }
        }
    }
}
