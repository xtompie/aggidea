<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class ProjectionToTupleMpper
{
    public function tuple(array $projection): array
    {
        $tuple = $projection['id'] + $projection['data'] ?? [];
        foreach ($tuple['records'] ?? [] as $group => $records) {
            foreach ($records as $index => $record) {
                $tuple['records'][$group][$index] = $this->tuple($record);
            }
        }
        return $tuple;
    }

}
