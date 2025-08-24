<?php

namespace src\Domain\Product\Enums;

enum OutboxStatusEnum : string
{
    const string PENDING = "pending";
    const string PROCESSED = "processed";
    const string FAILED = "failed";
}
