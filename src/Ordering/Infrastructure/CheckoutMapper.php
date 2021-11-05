<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Checkout;

class CheckoutMapper
{
    public function model(array $tuple): Checkout
    {
        return new Checkout(
            id: $tuple['id'],
        );
    }

    public function primitive(Checkout $checkout): array
    {
        return [
            'id' => $checkout->id(),
        ];
    }
}
