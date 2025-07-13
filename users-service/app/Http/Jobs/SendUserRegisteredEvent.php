<?php

namespace App\Http\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendUserRegisteredEvent implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Dispatchable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('User Registered Event Sent to RabbitMQ', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
        ]);
    }
}
