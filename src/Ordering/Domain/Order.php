<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

use Xtompie\Aggidea\Shared\Domain\Aggregate;
use Xtompie\Aggidea\Shared\Domain\ContactAddress;

class Order
{
    public function __construct(
        protected string $id,
        protected OrderStatus $status,
        protected ContactAddress $billingAddress,
        protected OrderSellerCollection $sellers,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function billingAddress(): ContactAddress
    {
        return $this->billingAddress;
    }

    public function sellers(): OrderSellerCollection
    {
        return $this->sellers;
    }

}
