<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

class ProjectionPresister
{
    public function __construct(
        protected ProjectionPresisterDAO $dao,
    ) {}

    public function presist(?array $future, callable $presentProvider)
    {
        $this->dao->transaction(function () use ($future, $presentProvider) {
            $present = $presentProvider();
            $this->synchronizeProjections($present, $future);
        });
    }

    public function synchronizeProjections(?array $present, ?array $future)
    {
        $this->synchronizeRecords($this->records($present), $this->records($future));
    }

    public function synchronizeRecords(array $present, array $future)
    {
        foreach ($this->computeInserts($present, $future) as $r) {
            $this->dao->insert($r['table'], $r['id'], $r['data']);
        }
        foreach ($this->computeUpdates($present, $future) as $r) {
            $this->dao->update($r['table'], $r['id'], $r['data']);
        }
        foreach ($this->computeDeletes($present, $future) as $r) {
            $this->dao->delete($r['table'], $r['id']);
        }
    }

    protected function records(?array $projection): array
    {
        if ($projection === null) {
            return [];
        }

        $records = $this->record($projection);

        foreach ($projection as $projection_value) {
            if (is_array($projection_value)) {
                foreach ($projection_value as $child) {
                    $records += $this->records($child);
                }
            }
        }

        return $records;
    }

    protected function record($projection): array
    {
        $table = $projection[':table'];
        $id = $projection['id'];
        unset($projection[':table'], $projection['id']);
        foreach ($projection as $key => $value) {
            if (is_array($value)) {
                unset($projection[$key]);
            }
        }
        $data = $projection;

        return [
            $table . ':' . $id => [
                'table' => $table,
                'id' => $id,
                'data' => $data,
                'state' => sha1(serialize($data)),
            ]
        ];
    }

    protected function computeInserts(array $present, array $future)
    {
        $computed = [];
        foreach (array_diff(array_keys($future), array_keys($present)) as $id) {
            $computed[$id] = $future[$id];
        }
        return $computed;
    }

    protected function computeUpdates(array $present, array $future)
    {
        $computed = [];
        foreach (array_intersect(array_keys($present), array_keys($future)) as $id) {
            if ($present[$id]['state'] !== $future[$id]['state']) {
                $computed[$id] = $future[$id];
            }
        }
        return $computed;
    }

    protected function computeDeletes(array $present, array $future)
    {
        $computed = [];
        foreach (array_diff(array_keys($present), array_keys($future)) as $id) {
            $computed[$id] = $future[$id];
        }
        return $computed;
    }
}