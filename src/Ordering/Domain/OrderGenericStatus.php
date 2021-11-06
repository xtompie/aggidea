<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Ordering\Domain;

class OrderGenericStatus
{
    public function __construct(
        protected string $status,
    ) {}

    public function __toString()
    {
        return $this->status;
    }
}