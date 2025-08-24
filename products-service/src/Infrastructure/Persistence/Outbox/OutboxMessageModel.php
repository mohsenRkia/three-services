<?php

namespace src\Infrastructure\Persistence\Outbox;

use Illuminate\Database\Eloquent\Model;

class OutboxMessageModel extends Model
{
    protected $table = 'outbox_messages';
    protected $fillable = [
        'aggregate_type',
        'aggregate_id',
        'event_type',
        'payload',
        'status',
        'processed_at',
    ];
}
