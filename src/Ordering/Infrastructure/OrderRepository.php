<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Domain\OrderCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderRepository as DomainOrderRepository;
use Xtompie\Aggidea\Shared\Infrastructure\AggregatePresent;
use Xtompie\Aggidea\Shared\Infrastructure\AggregatePresister;
use Xtompie\Aggidea\Shared\Infrastructure\Arr;
use Xtompie\Aggidea\Shared\Infrastructure\TupleDAO;

class OrderRepository implements DomainOrderRepository
{
    public function __construct(
        protected AggregatePresister $presister,
        protected OrderORM $orderORM,
        protected TupleDAO $tupleDAO,
    ) {}

    public function findById(string $id): ?Order
    {
        $tuple = $this->tuples(['id' => $id])[0] ?? null;
        if ($tuple === null) {
            return null;
        }
        return $this->orderOrm->model($tuple);
    }

    public function findAllByIds(array $ids): OrderCollection
    {
        return new OrderCollection(Arr::map(
            $this->tuples(['id' => $ids]),
            fn(array $tuple) => $this->orderORM->aggregate($tuple),
        ));
    }

    public function save(Order $order)
    {
        $this->presister->presist($order, $this->orderORM, fn() => $this->findById($order->id()));
    }

    public function remove(Order $order)
    {
        $this->presister->presist(null, $this->orderORM, fn() => $this->findById($order->id()));
    }

    protected function tuples($tql): array
    {
        return $this->tupleDAO->query($this->orderORM->tql($tql));
    }
}
