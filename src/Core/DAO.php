<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

use Exception;
use PDO;

class DAO
{
    protected callable $escaper;

    public function __construct(
        protected PDO $pdo,
    ) {
        $this->escaper = function ($string) {
            return $this->pdo->quote($string);
        };
    }

    public function escaper(): callable
    {
        return $this->escaper;
    }

    public function escape($string): string
    {
        return $this->pdo->quote($string);
    }

    public function command(string $sql)
    {
        $this->pdo->query($sql);
    }

    public function query(string $sql): array
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function transaction(callable $callback)
    {
        if ($this->pdo->inTransaction()) {
            $callback();
            return;
        }

        if (!$this->pdo->beginTransaction()) {
            throw new Exception();
        }

        try {
            $callback();
            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
