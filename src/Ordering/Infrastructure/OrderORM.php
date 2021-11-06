<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Order;
use Xtompie\Aggidea\Ordering\Domain\OrderProduct;
use Xtompie\Aggidea\Ordering\Domain\OrderProductCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderSeller;
use Xtompie\Aggidea\Ordering\Domain\OrderSellerCollection;
use Xtompie\Aggidea\Ordering\Domain\OrderSellerStatus;
use Xtompie\Aggidea\Ordering\Domain\OrderStatus;
use Xtompie\Aggidea\Shared\Infrastructure\Arr;
use Xtompie\Aggidea\Shared\Infrastructure\ContactAddressMapper;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateORM;

class OrderORM implements AggregateORM
{
    public function __construct(
        protected ContactAddressMapper $contactAddressMapper,
        protected OrderMig $orderMig,
    ) {}

    public function model(array $tuple): Order
    {
        $tuple = $this->orderMig->mig($tuple);

        return new Order(
            id: $tuple['id'],
            status: new OrderStatus($tuple['status']),
            billingAddress: $this->contactAddressMapper->model($tuple['billing_address']),
            sellers: new OrderSellerCollection(Arr::map($tuple['sellers'], fn(array $seller) => new OrderSeller(
                sellerId: $seller['id'],
                status: new OrderSellerStatus($seller['status']),
                products: new OrderProductCollection(Arr::map($seller['products'], fn(array $product) => new OrderProduct(
                    id: $product['id'],
                    catalogProductId: $product['catalog_product_id'],
                    amount: $product['amount'],
                ))),
            ))),
        );
    }

    public function express(Order $order): array
    {
        return [
            ':table' => 'order',
            'id' => $order->id(),
            'billing_address' => $this->contactAddressMapper->primitive($order->billingAddress()),
            'sellers' => Arr::map($order->sellers()->all(), fn(OrderSeller $orderSeller, $index) => [
                'table' => 'order_seller',
                ':id' => [
                    'order_id' => $order->id(),
                    'seller_id' => $orderSeller->sellerId(),
                ],
                'index' => $index,
                'status' => $orderSeller->status()->__toString(),
                'products' => Arr::map($orderSeller->products()->all(), fn(OrderProduct $orderProduct, $index) => [
                    '_table' => 'order_products',
                    'id' => $orderProduct->id(),
                    'seller_id' => $orderSeller->sellerId(),
                    'index' => $index,
                    'catalogProductId' => $orderProduct->catalogProductId(),
                    'amount' => $orderProduct->amount(),
                ]),
            ])
        ];
    }

}
