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
use Xtompie\Aggidea\Shared\Infrastructure\ContactAddressSerializer;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateORM;

class OrderORM implements AggregateORM
{
    public function __construct(
        protected ContactAddressSerializer $contactAddressSerializer,
        protected OrderMig $orderMig,
    ) {}

    public function aggregate(array $tuple): Order
    {
        $tuple = $this->orderMig->mig($tuple);

        return new Order(
            id: $tuple['id'],
            status: new OrderStatus($tuple['status']),
            billingAddress: $this->contactAddressSerializer->model($tuple['billing_address']),
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

    public function projection(Order $order): array
    {
        return [
            'table' => 'order',
            'id' => [
                'id' => $order->id(),
            ],
            'data' => [
                'billing_address' => $this->contactAddressSerializer->primitive($order->billingAddress()),
            ],
            'records' => [
                'sellers' => Arr::map($order->sellers()->all(), fn(OrderSeller $orderSeller, $index) => [
                    'table' => 'order_seller',
                    'id' => [
                        'order_id' => $order->id(),
                        'seller_id' => $orderSeller->sellerId(),
                    ],
                    'data' => [
                        'index' => $index,
                        'status' => $orderSeller->status()->__toString(),
                    ],
                    'records' => [
                        'products' => Arr::map($orderSeller->products()->all(), fn(OrderProduct $orderProduct, $index) => [
                            'table' => 'order_products',
                            'id' => [
                                'id' => $orderProduct->id(),
                            ],
                            'data' => [
                                'seller_id' => $orderSeller->sellerId(),
                                'index' => $index,
                                'catalogProductId' => $orderProduct->catalogProductId(),
                                'amount' => $orderProduct->amount(),
                            ],
                        ]),
                    ],
                ]),
            ],
        ];
    }

    public function tql(array $tql): array
    {
        return array_merge($tql, [
            'select' => '*',
            'from' => 'order',
            'tql:rel:sellers' => [
                'select' => '*',
                'from' => 'order_seller',
                'tql:parent:order_id' => 'id',
                'order' => 'index DESC',
                'tql:rel:products' => [
                    'select' => '*',
                    'from' => 'order_products',
                    'tql:parent:seller_id' => 'seller_id',
                    'order' => 'index DESC',
                ],
            ],
        ]);
    }
}
