<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use PDO;
use Xtompie\Aggidea\Ordering\Domain\OrderRevision;
use Xtompie\Aggidea\Ordering\Domain\OrderRevisionRepository as DomainOrderRevisionRepository;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateManager;

class OrderRevisionRepository implements DomainOrderRevisionRepository
{
    public function __construct(
        protected PDO $pdo,
        protected OrderRevisionORM $orderRevisionORM,
        protected AggregateManager $AggregateManager,
    ) {}

    public function findById(string $id): ?OrderRevision
    {
        $tuple = [];

        return $this->orderRevisionORM->model($tuple);
    }

    public function save(OrderRevision $order)
    {
        $this->AggregateManager->save($order, $this->OrderOrm);
    }
}
