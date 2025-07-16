<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Jobs\HandleUserRegisteredEvent;

class ConsumeUserRegistered extends Command
{
    protected $signature = 'rabbitmq:consume-user-registered';
    protected $description = 'Consume messages from user_registered queue and dispatch job';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $channel = $connection->channel();

        $channel->queue_declare('user_registered', false, true, false, false);

        $this->info(' [*] Waiting for messages from user_registered. To exit press CTRL+C');

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);

            // Dispatch Laravel Job to handle the message
            HandleUserRegisteredEvent::dispatch($data);
        };

        $channel->basic_consume('user_registered', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
