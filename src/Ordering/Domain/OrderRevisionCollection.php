<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderRevisionCollection
{
    public function __construct(
        protected array $collection,
    ) {}

    /**
     * @return OrderRevision[]
     */
    public function all(): array
    {
        return $this->collection;
    }
}
