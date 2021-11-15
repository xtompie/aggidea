<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Domain\OrderCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderProduct;
use Xtompie\Aggidea\Ordering\Domain\OrderProductCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderRepository as DomainOrderRepository;
use Xtompie\Aggidea\Ordering\Domain\OrderSeller;
use Xtompie\Aggidea\Ordering\Domain\OrderSellerCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderSellerStatus;
use Xtompie\Aggidea\Ordering\Domain\OrderStatus;
use Xtompie\Aggidea\Shared\Infrastructure\Arr;
use Xtompie\Aggidea\Shared\Infrastructure\ContactAddressSerializer;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionFetcher;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionPresister;

class OrderRepository implements DomainOrderRepository
{
    public function __construct(
        protected ContactAddressSerializer $contactAddressSerializer,
        protected ProjectionFetcher $projectionFetcher,
        protected ProjectionPresister $projectionPresister,
    ) {}

    public function findById(string $id): ?Order
    {
        $projection = $this->projectionFetcher->fetch($this->query(['id' => $id]));
        if (!$projection) {
            return null;
        }
        return $this->aggregate($projection);
    }

    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): OrderCollection
    {
        return new OrderCollection(Arr::map(
            $this->projectionFetcher->fetchAll($this->query($where, $order, $limit, $offset)),
            fn(array $projection) => $this->aggregate($projection),
        ));
    }

    public function save(Order $order)
    {
        $this->projectionPresister->presist(
            $this->projection($order),
            fn() => $this->projection($this->findById($order->id()))
        );
    }

    public function remove(Order $order)
    {
        $this->projectionPresister->presist(
            null,
            fn() => $this->projection($this->findById($order->id()))
        );
    }

    public function aggregate(array $projection): Order
    {
        $projection = $this->migrate($projection);

        return new Order(
            id: $projection['id'],
            status: new OrderStatus($projection['status']),
            billingAddress: $this->contactAddressSerializer->model($projection['billing_address']),
            sellers: new OrderSellerCollection(Arr::map($projection['sellers'], fn(array $seller) => new OrderSeller(
                sellerId: $seller['seller_id'],
                status: new OrderSellerStatus($seller['status']),
                products: new OrderProductCollection(Arr::map($seller['products'], fn(array $product) => new OrderProduct(
                    id: $product['id'],
                    catalogProductId: $product['catalog_product_id'],
                    amount: $product['amount'],
                ))),
            ))),
        );
    }

    public function projection(Order $order): array
    {
        return [
            ':table' => 'orders',
            'id' => $order->id(),
            'billing_address' => $this->contactAddressSerializer->primitive($order->billingAddress()),
            'sellers' => Arr::map($order->sellers()->all(), fn(OrderSeller $orderSeller, $index) => [
                ':table' => 'order_seller',
                'id' => $order->id() . ':' . $orderSeller->sellerId(),
                'order_id' => $order->id(),
                'seller_id' => $orderSeller->sellerId(),
                'index' => $index,
                'status' => $orderSeller->status()->__toString(),
                'products' => Arr::map($orderSeller->products()->all(), fn(OrderProduct $orderProduct, $index) => [
                    ':table' => 'order_products',
                    'id' => $orderProduct->id(),
                    'order_id' => $order->id(),
                    'seller_id' => $orderSeller->sellerId(),
                    'index' => $index,
                    'catalog_product_id' => $orderProduct->catalogProductId(),
                    'amount' => $orderProduct->amount(),
                ]),
            ]),
        ];
    }

    protected function query(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return [
            'from' => 'orders',
            'where' => $where,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset,
            'pql:children:sellers' => [
                'from' => 'order_seller',
                'order' => 'index DESC',
                'pql:parent' => 'order_id',
                'pql:children:products' => [
                    'from' => 'order_products',
                    'pql:parent' => 'order_seller_id',
                    'order' => 'index DESC',
                ],
            ],
        ];
    }

    protected function migrate(array $projection): array
    {
        return $projection;
    }
}