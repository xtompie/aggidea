<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class ProjectionFetcher
{
    protected callable $fetcher;

    public function __construct(
    ) {}

    public function fetch(array $pql): ?array
    {
        return $this->fetchAll($pql)[0] ?? null;
    }

    public function fetchAll(array $pql): array
    {
        $projections = [];
        foreach ($this->findTasks($pql, '*') as $task) {
            $this->processTask($projections, $task);
        }

        // $this->process(null, $projections, $pql);
        return [];
    }

    protected function findTasks($pql, $path)
    {
        $tasks[] = $this->createTask($pql, $path);
        foreach((array) $pql['pql:records'] as $records) {
            foreach($records as $records_name => $records_pql) {
                $tasks[] = $this->findTasks($records_pql, $path . '.records.' . $records_name . '.*');
            }
        }
        return $tasks;
    }

    protected function createTask($pql, $path)
    {
        $task = [
            'query' => $pql,
            'identity' => $pql['pql:identity'],
            'path' => $path,
            'parent' => [],
        ];
        if (!isset($task['query']['select'])) {
            $task['query']['select'] = '*';
        }
        if (isset($task['query']['pql:table'])) {
            $task['query']['from'] = $task['query']['pql:table'];
        }

        $parent_prefix = 'pql:parent:';
        foreach ($task['query'] as $k => $v) {
            if (!str_starts_with($k, $parent_prefix)) {
                continue;
            }
            $task['parent'][substr($k, strlen($parent_prefix))] = $v;
            unset($task['query'][$k]);
        }
        unset($task['query']['pql:table'], $task['query']['pql:identity']);

        return $task;
    }

    protected function processTask(&$projections, $task)
    {

    }
}
