<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

class Arr
{
    public static function map(array $array, callable $callback): array
    {
        return array_map($callback, $array);
    }
}
