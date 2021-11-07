<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use Exception;

class AggregatePresisterDAO
{
    public function __construct(
        protected DAO $dao,
        protected ACQL $acql,
    ) {}

    public function select(string $table, array $id): ?array
    {
        return $this->dao->query($this->acql->query(
            $id + ['from' => $table],
            $this->dao->escaper(),
        ))[0] ?? null;
    }

    public function insert(string $table, array $id, array $data)
    {
        $this->dao->command($this->acql->command(
            ['insert' => $table, 'set' => $id + $data],
            $this->dao->escaper(),
        ));
    }

    public function update(string $table, array $id, array $data)
    {
        if (!$id) {
            throw new Exception();
        }
        $this->dao->command($this->acql->command(
            ['update' => $table, 'set' => $data, 'where' => $id],
            $this->dao->escaper(),
        ));
    }

    public function delete(string $table, array $id)
    {
        if (!$id) {
            throw new Exception();
        }
        $this->dao->command($this->acql->command(
            ['delete' => $table, 'where' => $id],
            $this->dao->escaper(),
        ));
    }

    public function transaction(callable $callback)
    {
        $this->dao->transaction($callback);
    }
}
