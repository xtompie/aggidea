<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

interface OrderRevisionRepository
{
    public function findById(string $id): ?OrderRevision;
    public function save(OrderRevision $orderRevision);
}
