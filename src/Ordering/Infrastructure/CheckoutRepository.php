<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Checkout;
use Xtompie\Aggidea\Ordering\Domain\CheckoutRepository as DomainCheckoutRepository;

class CheckoutRepository implements DomainCheckoutRepository
{
    public function __construct(
        protected CheckoutORM $checkoutORM,
    ) {}

    public function findById(string $id): ?Checkout
    {
        $checkout = $_SESSION['checkout'][$id];
        if (!$checkout) {
            return null;
        }
        return $this->checkoutORM->aggregate($checkout);
    }

    public function save(Checkout $checkout)
    {
        $_SESSION['checkout'][$checkout->id()] = $this->checkoutORM->projection($checkout)->value();
    }
}
