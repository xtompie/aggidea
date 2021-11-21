<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Core;

class ProjectionDAO
{
    public function __construct(
        protected ProjectionFetcher $projectionFetcher,
        protected ProjectionPresister $projectionPresister,
    ) {}

    public function fetch(array $pql): ?array
    {
        return $this->projectionFetcher->fetch($pql);
    }

    public function fetchAll(array $pql): array
    {
        return $this->projectionFetcher->fetchAll($pql);
    }

    public function presist(?array $future, callable $presentProvider)
    {
        return $this->projectionPresister->presist($future, $presentProvider);
    }

    public function remove(callable $presentProvider)
    {
        return $this->projectionPresister->remove($presentProvider);
    }

}