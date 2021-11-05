<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Domain\CheckoutRepository as DomainCheckoutRepository;

class OrderRepository implements DomainOrderRepository
{
    public function __construct(
        protected PDO $pdo,
        protected OrderMapper $orderMapper,
        protected EntityManager $entityManager,
    ) {}

    public function findById(string $id): ?Order
    {
        $order = $_SESSION['order'][$id];
        if (!$order) {
            return null;
        }
        return $this->orderMapper->model($order);
    }

    public function save(Order $order)
    {
        $_SESSION['order'][$order->id()] = $this->orderMapper->primitive($order);
    }
}
