<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Checkout;

class CheckoutORM
{
    public function aggregate(array $tuple): Checkout
    {
        return new Checkout(
            id: $tuple['id'],
        );
    }

    public function projection(Checkout $checkout): array
    {
        return [
            'id' => $checkout->id(),
        ];
    }
}
