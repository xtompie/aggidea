<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Checkout;
use Xtompie\Aggidea\Ordering\Domain\CheckoutRepository as DomainCheckoutRepository;

class CheckoutRepository implements DomainCheckoutRepository
{
    public function findById(string $id): ?Checkout
    {
        $checkout = $_SESSION['checkout'][$id];
        if (!$checkout) {
            return null;
        }
        return $this->aggregate($checkout);
    }

    public function save(Checkout $checkout)
    {
        $_SESSION['checkout'][$checkout->id()] = $this->tuple($checkout);
    }

    public function aggregate(array $tuple): Checkout
    {
        return new Checkout(
            id: $tuple['id'],
        );
    }

    public function tuple(Checkout $checkout): array
    {
        return [
            'id' => $checkout->id(),
        ];
    }
}
