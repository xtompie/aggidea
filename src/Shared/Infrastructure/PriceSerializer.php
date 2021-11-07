<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use Xtompie\Aggidea\Shared\Domain\Price;

class PriceSerializer
{
    public function model(int $primitive): Price
    {
        return new Price($primitive);
    }

    public function scalar(Price $price): int
    {
        return $price->value();
    }
}
