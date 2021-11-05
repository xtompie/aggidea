<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class Checkout
{
    public function __construct(
        protected string $id,
    ) {}

    public function id(): string
    {
        return $this->id;
    }
}
