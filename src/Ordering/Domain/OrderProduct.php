<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderProduct
{
    public function __construct(
        protected string $id,
        protected string $catalogProductId,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function catalogProductId(): string
    {
        return $this->catalogProductId;
    }
}
