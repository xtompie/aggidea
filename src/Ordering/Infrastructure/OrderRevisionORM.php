<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\OrderRevision;
use Xtompie\Aggidea\Shared\Domain\Time;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateORM;
use Xtompie\Aggidea\Shared\Infrastructure\OrderSerializer;

class OrderRevisionORM implements AggregateORM
{
    public function __construct(
        protected OrderSerializer $orderSerializer,
    ) {}

    public function aggregate(array $tuple): OrderRevision
    {
        return new OrderRevision(
            id: $tuple['id'],
            createdAt: new Time($tuple['created_at']),
            order: $this->orderSerializer->model($tuple['order']),
        );
    }

    public function projection(OrderRevision $orderRevision): array
    {
        return [
            'table' => 'order_revision',
            'id' => [
                'id' => $orderRevision->id()
            ],
            'data' => [
                'created_at' => $orderRevision->createdAt()->__toString(),
                'order' => $this->orderSerializer->primitive($orderRevision->order()),
            ]
        ];
    }
}
