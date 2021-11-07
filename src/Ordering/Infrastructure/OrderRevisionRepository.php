<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\OrderRevision;
use Xtompie\Aggidea\Ordering\Domain\OrderRevisionCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderRevisionRepository as DomainOrderRevisionRepository;
use Xtompie\Aggidea\Shared\Domain\Time;
use Xtompie\Aggidea\Shared\Infrastructure\Arr;
use Xtompie\Aggidea\Shared\Infrastructure\OrderSerializer;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionFetcher;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionPresister;

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

    public function aggregate(array $projection): OrderRevision
    {
        $projection = $this->migrate($projection);

        return new OrderRevision(
            id: $projection['identity']['id'],
            createdAt: new Time($projection['data']['created_at']),
            order: $this->orderSerializer->model($projection['data']['order']),
        );
    }

    public function projection(OrderRevision $orderRevision): array
    {
        return [
            'table' => 'order_revisions',
            'identity' => [
                'id' => $orderRevision->id(),
            ],
            'data' => [
                'created_at' => $orderRevision->createdAt()->__toString(),
                'order' => $this->orderSerializer->primitive($orderRevision->order()),
            ],
        ];
    }

    protected function query(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return [
            'select' => '*',
            'from' => 'order_revisions',
            'where' => $where,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset,
            'pql:identity' => ['id'],
        ];
    }

    protected function migrate(array $projection): array
    {
        return $projection;
    }
}
