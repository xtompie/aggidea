<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

interface AggregateORM
{
    public function aggregate(array $tuple): object;
    public function projection(object $model): array;
}
