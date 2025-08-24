<?php

namespace Messaging;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use src\Domain\Product\Events\ProductCreated;

class RabbitMQMessageBus
{
    private string $host = "rabbitmq";
    private int $port = 5672;
    private string $user = "guest";
    private string $password = "guest";
    private string $exchange;

    public function publish(string $event_type,ProductCreated $payload): void
    {
        $connection = new AMQPStreamConnection(
            $this->host, $this->port, $this->user, $this->password
        );

        $channel = $connection->channel();
        $channel->exchange_declare($this->exchange, 'topic', false, true, false);

        $message = new AMQPMessage(json_encode($payload), [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($message, $this->exchange, $event_type);
        $channel->close();
        $connection->close();
    }
}
