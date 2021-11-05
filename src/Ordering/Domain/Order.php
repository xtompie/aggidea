<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class Order
{
    public function __construct(
        protected string $id,
        protected string $billingAddress,
        protected OrderSellerCollection $sellers,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function billingAddress(): string
    {
        return $this->billingAddress;
    }

    public function sellers(): OrderSellerCollection
    {
        return $this->sellers;
    }

}
