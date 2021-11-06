<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

use Xtompie\Aggidea\Shared\Domain\Time;

class OrderRevision
{
    public function __construct(
        protected string $id,
        protected Time $createdAt,
        protected Order $order,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function createdAt(): Time
    {
        return $this->createdAt;
    }

    public function order(): Order
    {
        return $this->order;
    }
}
