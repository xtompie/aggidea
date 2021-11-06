<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use PDO;
use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Domain\OrderRepository as DomainOrderRepository;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateManager;
use Xtompie\Aggidea\Shared\Infrastructure\AggregatePresister;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateProvider;

class OrderRepository implements DomainOrderRepository, AggregateProvider
{
    public function __construct(
        protected PDO $pdo,
        protected OrderORM $orderOrm,
        protected AggregatePresister $presister,
    ) {}

    public function findById(string $id): ?Order
    {

        $q = [
            ':from' => 'order',
            'id' => $id,
            'sellers' => [
                ':from' => 'order_seller',
                'order_id' => '$parent.id',
                ':order' => 'index DESC',
                'products' => [
                    ':from' => 'order_products',
                    'seller_id' => '$parent.id',
                    ':order' => 'index DESC',
                ]
            ]

        ];
        $tuple = [];

        return $this->orderOrm->model($tuple);
    }

    public function save(Order $order)
    {
        $this->presister->presist($this, $this->OrderOrm, $order);
    }
}
