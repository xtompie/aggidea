<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\OrderRevision;
use Xtompie\Aggidea\Shared\Domain\Time;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateORM;

class OrderRevisionMapper implements AggregateORM
{
    public function __construct(
        protected OrderOrm $OrderOrm,
    ) {}

    public function model(array $tuple): OrderRevision
    {
        return new OrderRevision(
            id: $tuple['id'],
            createdAt: new Time($tuple['created_at']),
            order: $this->OrderOrm->model(json_decode($tuple['order'])),
        );
    }

    public function express(OrderRevision $orderRevision): array
    {
        return [
            '_table' => 'order_revision',
            'id' => $orderRevision->id(),
            'created_at' => $orderRevision->createdAt()->__toString(),
            'order' => json_encode($this->OrderOrm->express($orderRevision->order())),
        ];
    }

}
