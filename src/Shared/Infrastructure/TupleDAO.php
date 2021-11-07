<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class TupleDAO
{
    protected callable $fetcher;

    public function __construct(
        protected DAO $dao,
        protected TQL $tql,
    ) {
        $this->fetcher = function ($sql) {
            return $this->dao->query($sql);
        };
    }

    public function query(array $tql): ?array
    {
        return $this->tql->query($tql, $this->dao->escaper(), $this->fetcher);
    }
}
