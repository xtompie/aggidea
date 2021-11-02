<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Domain;

use Money\Currency;
use Money\Money;

final class Price
{
    protected Money $money;

    public function __construct(int $value)
    {
        $this->money = new Money($value, new Currency('EUR'));
    }

    public function value(): int
    {
        return (int)$this->money->getAmount();
    }

    public function add(Price $price): self
    {
        return new self(
            (int)$this->money->add($price->money)
        );
    }
}
