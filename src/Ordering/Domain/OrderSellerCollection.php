<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderSellerCollection
{
    public function __construct(
        protected array $collection,
    ) {}

    /**
     * @return OrderSeller[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function findBySellerId(string $id): ?OrderSeller
    {
        return null;
    }
}
