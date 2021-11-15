<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Core\Arr;
use Xtompie\Aggidea\Core\ProjectionFetcher;
use Xtompie\Aggidea\Core\ProjectionPresister;
use Xtompie\Aggidea\Ordering\Domain\OrderRevision;
use Xtompie\Aggidea\Ordering\Domain\OrderRevisionCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderRevisionRepository as DomainOrderRevisionRepository;
use Xtompie\Aggidea\Shared\Domain\Time;

class OrderRevisionRepository implements DomainOrderRevisionRepository
{
    public function __construct(
        protected OrderSerializer $orderSerializer,
        protected ProjectionFetcher $projectionFetcher,
        protected ProjectionPresister $projectionPresister,
    ) {}

    public function findById(string $id): ?OrderRevision
    {
        $projection = $this->projectionFetcher->fetch($this->query(['id' => $id]));
        if (!$projection) {
            return null;
        }
        return $this->aggregate($projection);
    }

    public function findAllByOrderId(
        string $orderId, ?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null
    ): OrderRevisionCollection
    {
        return new OrderRevisionCollection(Arr::map(
            $this->projectionFetcher->fetchAll(
                $this->query(
                    ['order_id' => $orderId] + $where,
                    $order, $limit, $offset
                ))
            ,
            fn(array $projection) => $this->aggregate($projection),
        ));
    }

    public function save(OrderRevision $orderRevision)
    {
        return $this->projectionPresister->presist(
            $this->projection($orderRevision),
            fn() => $this->projection($this->findById($orderRevision->id()))
        );
    }

    protected function aggregate(array $projection): OrderRevision
    {
        $projection = $this->migrate($projection);

        return new OrderRevision(
            id: $projection['id'],
            createdAt: new Time($projection['created_at']),
            order: $this->orderSerializer->model($projection['order']),
        );
    }

    protected function projection(OrderRevision $orderRevision): array
    {
        return [
            ':table' => 'order_revisions',
            'id' => $orderRevision->id(),
            'created_at' => $orderRevision->createdAt()->__toString(),
            'order' => $this->orderSerializer->primitive($orderRevision->order()),
        ];
    }

    protected function query(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return [
            'from' => 'order_revisions',
            'where' => $where,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset,
        ];
    }

    protected function migrate(array $projection): array
    {
        return $projection;
    }
}
