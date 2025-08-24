<?php

namespace src\Infrastructure\Outbox\Console;

use Illuminate\Console\Command;
use src\Infrastructure\Outbox\OutboxDispatcher;

class DispatchOutboxMessagesCommand extends Command
{
    protected $signature = 'outbox:dispatch';
    protected $description = 'Dispatch pending outbox messages to RabbitMQ';

    public function __construct(private OutboxDispatcher $dispatcher)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->dispatcher->handle();
        return Command::SUCCESS;
    }
}
