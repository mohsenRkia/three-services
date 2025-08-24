<?php

namespace src\Infrastructure\Outbox\Console;

use Illuminate\Console\Command;
use src\Infrastructure\Outbox\OutboxConsumer;
use src\Infrastructure\Outbox\OutboxDispatcher;

class ConsumeOutboxMessagesCommand extends Command
{
    protected $signature = 'outbox:consume';
    protected $description = 'Consume outbox messages from RabbitMQ';

    public function __construct(private OutboxConsumer $consumer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->consumer->handle();
        return Command::SUCCESS;
    }
}
