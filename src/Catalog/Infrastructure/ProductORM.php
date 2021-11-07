<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Infrastructure;

use Xtompie\Aggidea\Catalog\Domain\Product;
use Xtompie\Aggidea\Shared\Infrastructure\AggregateORM;
use Xtompie\Aggidea\Shared\Infrastructure\PriceSerializer;

class ProductORM implements AggregateORM
{
    public function __construct(
        protected PriceSerializer $priceSerializer,
    ) {}

    public function aggregate(array $tuple): Product
    {
        return new Product(
            id: $tuple['id'],
            title: $tuple['title'],
            price: $this->priceSerializer->model($tuple['price']),
        );
    }

    public function projection(Product $product): array
    {
        return [
            ':table' => 'articles',
            'id' => $product->id(),
            'title' => $product->title(),
            'price' => $this->priceSerializer->primitive($product->price()),
        ];
    }
}
