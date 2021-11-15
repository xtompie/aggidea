<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

use Exception;

class ProjectionPresisterDAO
{
    public function __construct(
        protected DAO $dao,
        protected AQL $aql,
    ) {}

    public function insert(string $table, array $identity, array $data)
    {
        $this->dao->command($this->aql->command(
            ['insert' => $table, 'set' => $identity + $data],
            $this->dao->escaper(),
        ));
    }

    public function update(string $table, array $identity, array $data)
    {
        if (!$identity) {
            throw new Exception();
        }
        $this->dao->command($this->aql->command(
            ['update' => $table, 'set' => $data, 'where' => $identity],
            $this->dao->escaper(),
        ));
    }

    public function delete(string $table, array $identity)
    {
        if (!$identity) {
            throw new Exception();
        }
        $this->dao->command($this->aql->command(
            ['delete' => $table, 'where' => $identity],
            $this->dao->escaper(),
        ));
    }

    public function transaction(callable $callback)
    {
        $this->dao->transaction($callback);
    }
}
