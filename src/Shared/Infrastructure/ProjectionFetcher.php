<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

class ProjectionFetcher
{
    protected callable $fetcher;

    public function __construct(
    ) {
    }

    public function fetch(array $pql): ?array
    {
        return $this->fetchAll($pql)[0] ?? null;
    }

    public function fetchAll(array $pql): array
    {
        return [];
    }
}
    /**
     * Fetches tuples and related tuples
     *
     * $tql options ACQL and:
     *
     * - ':tql:rel:<name>' => <TQL> - auto fetches related tuples
     * - ':tql:parent:<this_field>' => '<parent_field>'
     *
     * eg. [
     *  ':select' => '*'
     *  ':from' => 'articles',
     *  ':order' => 'index ASC',
     *  ':tql:rel:paragraphs' => [
     *    ':tql:parent:article_id' => 'id',
     *    ':select' => '*'
     *    ':from' => 'paragraphs',
     *    ':order' => 'index ASC',
     *    ':tql:rel:words' => [
     *      ':tql:parent:paragraph_id' => 'id',
     *      ':select' => '*'
     *      ':from' => 'words',
     *      ':order' => 'index ASC',
     *    ],
     *  ],
     * ]
     * only 3 sql query will be executed
     *
     */

        // to pierwsze to tez task, ustawiam task i puszczam process
        // wyciagam relsy
        // queue z taskami przetwarzania relsow, task ma path
        // indeks gdzie co bylo