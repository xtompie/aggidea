<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class EntityMemory
{
    protected $memory = [];

    public function remember(array $express)
    {
        $this->memory[$express['_table']][$express['id']] = $express;
    }

    public function remind(array $express): ?array
    {
        return $this->memory[$express['_table']][$express['id']];
    }

    public function forget()
    {
        $this->memory = [];
    }
}
