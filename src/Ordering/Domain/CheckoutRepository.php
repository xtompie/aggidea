<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

interface CheckoutRepository
{
    public function findById(string $id): ?Checkout;
    public function save(Checkout $checkout);
}

