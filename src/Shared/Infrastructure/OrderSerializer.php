<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Infrastructure\OrderORM;

class OrderSerializer
{
    public function __construct(
        protected ProjectionToTupleMpper $projectionToTupleMpper,
        protected OrderORM $orderORM,
    ) {}

    public function model(string $primitive): Order
    {
        return $this->orderORM->aggregate(json_decode($primitive));
    }

    public function primitive(Order $order): string
    {
        return json_encode(
            $this->projectionToTupleMpper->tuple(
                $this->orderORM->projection($order)
            )
            );
    }
}
