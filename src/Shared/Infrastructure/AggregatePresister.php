<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class AggregatePresister
{
    public function __construct(
        protected AggregatePresisterDAO $dao,
    ) {}

    public function presist(?object $aggregate, AggregateORM $orm, callable $presentAggregateProvider)
    {
        $this->dao->transaction(function () use ($aggregate, $orm, $presentAggregateProvider) {
            $present = $presentAggregateProvider($aggregate);
            $this->synchronizeProjections(
                $present === null ? null : $orm->projection($present),
                $aggregate === null ? null : $orm->projection($aggregate),
            );
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

        foreach ($projection['records'] ?? [] as $value) {
            foreach ($value as $child) {
                $records += $this->records($child);
            }
        }

        return $records;
    }

    protected function record($projection): array
    {
        return [
            sha1(serialize([$projection['table'], $projection['id']])) => [
                'table' => $projection['table'],
                'id' => $projection['id'],
                'data' => $projection['data'] ?? [],
                'state' => sha1(serialize($projection['data']))
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
