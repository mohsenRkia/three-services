<?php

namespace src\Infrastructure\Messaging;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQTestConsumer extends RabbitMQ
{
    private string $queue = "product_test_queue";
    private string $routingKey = "ProductCreated"; //event_type

    public function listen()
    {
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password);

        $channel = $connection->channel();

        $channel->exchange_declare($this->exchange, $this->exchange_type, false, true, false);

        list($queue_name, ,) = $channel->queue_declare($this->queue, false, true, false, false);

        $channel->queue_bind($queue_name, $this->exchange, $this->routingKey);

        echo "[*] Waiting for messages on queue '{$this->queue}' with binding key '{$this->routingKey}'...";

        $callback = function ($msg) {
            echo "[x] Received: " . $msg->body . "//////";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
