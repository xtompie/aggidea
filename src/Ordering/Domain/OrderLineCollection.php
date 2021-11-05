<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderLineCollection
{
    public function __construct(
        protected array $collection,
    ) {}

    /**
     * @return OrderLine[]
     */
    public function all(): array
    {
        return $this->collection;
    }
}
