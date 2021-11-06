<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderSeller
{
    public function __construct(
        protected string $sellerId,
        protected OrderSellerStatus $status,
        protected OrderProductCollection $products,
    ) {}

    public function sellerId(): string
    {
        return $this->sellerId;
    }

    public function status(): OrderSellerStatus
    {
        return $this->status;
    }

    public function products(): OrderProductCollection
    {
        return $this->products;
    }
}
