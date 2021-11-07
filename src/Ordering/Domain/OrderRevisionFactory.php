<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

use Xtompie\Aggidea\Shared\Domain\Time;
use Xtompie\Aggidea\Shared\Infrastructure\IdFactory;

class OrderRevisionFactory
{
    public function __construct(
        protected IdFactory $IdFactory,
    ) {}

    public function create(Order $order): OrderRevision
    {
        return new OrderRevision(
            id: $this->IdFactory->id(),
            createdAt: Time::now(),
            order: $order,
        );
    }
}