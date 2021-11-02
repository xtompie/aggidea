<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

interface EntityMapper
{
    public function model(array $tuple): object;
    public function express(object $model): array;
}
