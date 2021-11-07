<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Catalog\Infrastructure;

use Xtompie\Aggidea\Catalog\Domain\Product;
use Xtompie\Aggidea\Catalog\Domain\ProductRepository as DomainProductRepository;
use Xtompie\Aggidea\Shared\Infrastructure\PriceSerializer;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionFetcher;
use Xtompie\Aggidea\Shared\Infrastructure\ProjectionPresister;

class ProductRepository implements DomainProductRepository
{
    public function __construct(
        protected PriceSerializer $priceSerializer,
        protected ProjectionFetcher $projectionFetcher,
        protected ProjectionPresister $projectionPresister,
    ) {}

    public function findById(string $id): ?Product
    {
        $projection = $this->projectionFetcher->fetch($this->query(['id' => $id]));
        if (!$projection) {
            return null;
        }
        return $this->aggregate($projection);
    }

    public function save(Product $product)
    {
        return $this->projectionPresister->presist(
            $this->projection($product),
            fn() => $this->projection($this->findById($product->id()))
        );
    }

    public function remove(Product $product)
    {
        return $this->projectionPresister->presist(
            null,
            fn() => $this->projection($this->findById($product->id()))
        );
    }

    public function aggregate(array $projection): Product
    {
        $projection = $this->migrate($projection);

        return new Product(
            id: $projection['identity']['id'],
            title: $projection['data']['title'],
            price: $this->priceSerializer->model($projection['data']['price']),
        );
    }

    public function projection(Product $product): array
    {
        return [
            'table' => 'products',
            'identity' => [
                'id' => $product->id(),
            ],
            'data' => [
                'title' => $product->title(),
                'price' => $this->priceSerializer->primitive($product->price()),
            ],
        ];
    }

    protected function query(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return [
            'select' => '*',
            'from' => 'products',
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
