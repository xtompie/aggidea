<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

interface AggregateProvider
{
    public function findById(string $id): object;
}
