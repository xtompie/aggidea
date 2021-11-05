<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderSeller
{
    public function __construct(
        protected string $sellerId,
        protected OrderLineCollection $lines,
    ) {}

    public function sellerId(): string
    {
        return $this->sellerId;
    }

    public function lines(): OrderLineCollection
    {
        return new OrderLineCollection([]);
    }
}
