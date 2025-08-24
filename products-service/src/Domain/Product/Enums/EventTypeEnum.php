<?php

namespace src\Domain\Product\Enums;

enum EventTypeEnum : string
{
    const string CREATED = "ProductCreated";
    const string UPDATED = "ProductUpdated";
}
