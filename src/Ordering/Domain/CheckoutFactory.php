<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class CheckoutFactory
{
    public function create(string $id): Checkout
    {
        return new Checkout(
            id: $id,
        );
    }
}