<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderLine
{
    public function __construct(
        protected OrderProduct $product,
        protected int $amount,
    ) {}

    public function product(): OrderProduct
    {
        return $this->product;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}
