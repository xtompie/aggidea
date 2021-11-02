<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Infrastructure;

use Xtompie\Aggidea\Catalog\Domain\Product;
use Xtompie\Aggidea\Shared\Infrastructure\EntityMapper;
use Xtompie\Aggidea\Shared\Infrastructure\PriceMapper;

class ProductMapper implements EntityMapper
{
    public function __construct(
        protected PriceMapper $priceMapper,
    ) {}

    public function model(array $tuple): Product
    {
        return new Product(
            id: $tuple['id'],
            title: $tuple['title'],
            price: $this->priceMapper->model($tuple['price']),
        );
    }

    public function express(Product $product): array
    {
        return [
            '_table' => 'articles',
            'id' => $product->id(),
            'title' => $product->title(),
            'price' => $this->priceMapper->primitive($product->price()),
        ];
    }
}
