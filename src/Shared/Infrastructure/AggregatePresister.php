<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class AggregatePresister
{
    public function presist(AggregateProvider $provider, AggregateORM $orm, object $model)
    {
        // $new = $aggregateORM->express($model);
        // $old = $this->entityMemory->remind($new);
        // // $this->diff($old, $new); // @TODO
        // $this->entityMemory->remember($new);
        // return $model;
    }
}
