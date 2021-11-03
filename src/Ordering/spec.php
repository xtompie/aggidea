<?php

interface CheckoutRepository {
    public function getForCurrentUser(): Checkout;
    public function save(Checkout $checkout);
}
interface Checkout {
    public function sellers(): CheckoutSellerCollection;
    public function addProduct(string $productId, int $amount = 1): static;
    public function deleteProduct(string $productId): static;
    public function updateProductAmount(string $productId, int $amount): static;
    public function updateVoucher($voucherId);
    public function voucher(): string;
    public function sum(): Money;
    public function updateBillingAddress(Address $address);
    public function billingAddress(): ?Address;
    public function updateDeliveryAddress(Address $address);
    public function deliveryAddress(): ?Address;
    public function checkout(): Order;
}
interface CheckoutSellerCollection{}
interface CheckoutSeller{
    public function lines(): CheckoutLineCollection;
    public function deliveryId(): string;
}
interface CheckoutLineCollection{}
interface CheckoutLine{
    public function productId(): string;
    public function amount(): int;
}
interface VoucherRepository{
    public function findByCode(string $code): ?Voucher;
}
interface Voucher {
    public function apply(Money $money): Money;
}
interface Money {}
interface Address {}
interface OrderRepostiory {
    public function save(Order $order);
}
interface Order {
    public function sum(): Money;
    public function updateSum(): Money;
    public function updateBillingAddress(Address $address);
    public function billingAddress(): ?Address;
    public function updateDeliveryAddress(Address $address);
    public function deliveryAddress(): ?Address;
    public function sellers(): OrderSellerCollection;
}
interface OrderSellerCollection {
    public function findBySellerId(string $id);
}
interface OrderSeller {
    public function lines(): OrderLineCollection;
    public function deliveryId(): string;
}
interface OrderLineCollection{}
interface OrderLine{
    public function product(): OrderProduct;
    public function amount(): int;
}
interface OrderProduct {
}
interface OrderPlacedEvent {
    public function id(): string;
}
interface OrderPaymentCompletedEvent {
    public function id(): string;
}
interface OrderAcceptedEvent {
    public function id(): string;
}
interface OrderCompletedEvent{
    public function id(): string;
}

[
    '_table' => 'order',
    'id' => '',
    'user_id' => '',
    'sum' => $this->priceMapper->primitive($price),
    '_rel' => [
        'address' => [
            [
                '_table' => 'order_address',
                'id' => $order->address->billing->id,
                'street' => $order->address->billing->street,
                'type' => 'billing',
            ]
        ],
        'seller' => map($order->sellers(), fn (OrderSeller $orderSeller) => [
            '_table' => 'order_seller',
            'id' => $orderSeller->id(),
            'order_id' => $order->id(),
            'seller_id' => $orderSeller->sellerId(),
            'status' => 'new',
            '_rel'  => [
                'line' => map($orderSeller->lines(), fn(OrderLine $orderLine) => [
                    '_table' => 'order_article',
                    'id' => $orderLine->id(),
                    ''
                ]),
            ]
        ]),

    ]
];

interface db {

    public function upsert($table, $key, $data);
    public function delete($table, $ids);
}

function snapshoter()
{
    return Snapshooter::new()
        ->table('order')
        ->data(fn(Order $order) => [
            'id' => $order->id(),
            'user_id' => $order->userId(),
            'status' => $oder->status(),
        ])
        ->entities('seller', fn(Order $order) => $order->sellers(), )
}