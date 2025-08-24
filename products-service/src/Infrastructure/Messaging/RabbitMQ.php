<?php

namespace src\Infrastructure\Messaging;

class RabbitMQ
{
    protected string $host;
    protected int $port;
    protected string $user;
    protected string $password;

    protected string $exchange_type;
    protected string $exchange;

    public function __construct()
    {
        $this->host = env("RABBITMQ_HOST", "rabbitmq");
        $this->port = env("RABBITMQ_PORT", 5672);
        $this->user = env("RABBITMQ_USER", "guest");
        $this->password = env("RABBITMQ_PASSWORD", "guest");;
        $this->exchange_type = "topic";
        $this->exchange = "product_rabbitmq";
    }
}
