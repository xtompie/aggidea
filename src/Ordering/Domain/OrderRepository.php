<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

interface OrderRepository
{
    public function findById(string $id): ?Order;
    public function save(Order $order);
}
