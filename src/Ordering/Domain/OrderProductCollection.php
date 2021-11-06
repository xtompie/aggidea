<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderProductCollection
{
    public function __construct(
        protected array $collection,
    ) {}

    /**
     * @return OrderProduct[]
     */
    public function all(): array
    {
        return $this->collection;
    }
}
