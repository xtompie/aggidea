<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderCollection
{
    public function __construct(
        protected array $collection,
    ) {}

    /**
     * @return Order[]
     */
    public function all(): array
    {
        return $this->collection;
    }
}
