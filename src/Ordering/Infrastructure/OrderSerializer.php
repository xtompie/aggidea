<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Infrastructure\OrderRepository;

class OrderSerializer
{
    public function __construct(
        protected OrderRepository $orderRepository,
    ) {}

    public function model(string $primitive): Order
    {
        return $this->orderRepository->aggregate(json_decode($primitive));
    }

    public function primitive(Order $order): string
    {
        return json_encode($this->orderRepository->projection($order));
    }
}
