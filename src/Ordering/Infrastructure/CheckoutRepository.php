<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Infrastructure;

use Xtompie\Aggidea\Ordering\Domain\Checkout;
use Xtompie\Aggidea\Ordering\Domain\CheckoutRepository as DomainCheckoutRepository;

class CheckoutRepository implements DomainCheckoutRepository
{
    public function __construct(
        protected CheckoutMapper $checkoutMapper,
    ) {}

    public function findById(string $id): ?Checkout
    {
        $checkout = $_SESSION['checkout'][$id];
        if (!$checkout) {
            return null;
        }
        return $this->checkoutMapper->model($checkout);
    }

    public function save(Checkout $checkout)
    {
        $_SESSION['checkout'][$checkout->id()] = $this->checkoutMapper->primitive($checkout);
    }
}
