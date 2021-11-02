<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class EntityManager
{
    public function __construct(
        protected EntityMemory $entityMemory,
    ) {}

    public function load(array $tuple, EntityMapper $entityMapper): object
    {
        $model = $entityMapper->model($tuple);
        $this->entityMemory->remember($entityMapper->express($model));
        return $model;
    }

    public function save(object $model, EntityMapper $entityMapper): object
    {
        $new = $entityMapper->express($model);
        $old = $this->entityMemory->remind($new);
        // $this->diff($old, $new); // @TODO
        $this->entityMemory->remember($new);
        return $model;
    }

    public function clear()
    {
        $this->entityMemory->forget();
    }
}
