<?php

namespace Src\Domain\Product\ValueObjects;

use InvalidArgumentException;

class Price
{
    public function __construct(
        public readonly float $value,
    )
    {
        if ($value < 0){
            throw new InvalidArgumentException("Price cannot be less than 0.");
        }
    }
}
