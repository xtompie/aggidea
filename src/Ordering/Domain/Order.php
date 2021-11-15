<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

use Xtompie\Aggidea\Shared\Domain\ContactAddress;

class Order
{
    public function __construct(
        protected string $id,
        protected OrderStatus $status,
        protected ContactAddress $billingAddress,
        protected DeliveryMethod $deliveryMethod,
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

    public function paymentStatus(): OrderStatus
    {
        return $this->status;
    }

    public function billingAddress(): ContactAddress
    {
        return $this->billingAddress;
    }

    public function deliveryMethod(): DeliveryMethod
    {
        return $this->deliveryMethod;
    }

    public function sellers(): OrderSellerCollection
    {
        return $this->sellers;
    }

}
