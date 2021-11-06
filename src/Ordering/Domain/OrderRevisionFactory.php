<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

use Xtompie\Aggidea\Shared\Domain\Time;
use Xtompie\Aggidea\Shared\Infrastructure\IdGenerator;

class OrderRevisionFactory
{
    public function __construct(
        protected IdGenerator $idGenerator,
    ) {}

    public function create(Order $order): OrderRevision
    {
        return new OrderRevision(
            id: $this->idGenerator->id(),
            createdAt: Time::now(),
            order: $order,
        );
    }
}