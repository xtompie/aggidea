<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

class ProjectionFetcher
{
    protected $fetcher;

    public function __construct(
        protected AQL $aql,
        protected DAO $dao,
    ) {}

    public function fetch(array $pql): ?array
    {
        return $this->fetchAll($pql)[0] ?? null;
    }

    public function fetchAll(array $pql): array
    {
        return $this->pql($pql, []);
    }

    protected function pql(array $pql, array $parents): array
    {
        $projections = $this->dao->query($this->query($pql, $parents));
        $ids = array_column($projections, 'id');

        foreach ($pql as $key => $value) {
            if (str_starts_with($key, 'pql:children:')) {
                $field = substr($key, strlen('pql:children:'));
                $projections = $this->children($projections, $field, $value, $ids);
            }
        }

        return $projections;
    }

    protected function children($results, $field, $pql, $ids)
    {
        $children = $this->pql($pql, $ids);
        $pql_parent = $pql['pql:parent'];

        return Arr::map($results, fn($result) =>
            $result + [$field => array_filter($children, fn($child) => $child[$pql_parent] == $result['id'])]
        );
    }

    protected function query($pql, $parents): string
    {
        $query = $pql;

        if (!isset($query['select'])) {
            $query['select'] = '*';
        }

        foreach ($query as $key => $value) {
            if (str_starts_with($key, 'pql:')) {
                unset($query[$key]);
            }
        }

        if (isset($pql['pql:parent'])) {
            $query['where'][$pql['pql:parent'] . ':in'] = $parents;
        }

        return $query;
    }
}
