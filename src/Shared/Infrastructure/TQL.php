<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

/**
 * Tuple Query Language = ACQL + auto fetch related tuples
 */
class TQL
{
    public function __construct(
        protected ACQL $acql,
    ) {}

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
     * @param array $tql
     * @return string SQL statement
     */
    public function query(array $tql, callable $escaper, callable $fetcher): array
    {
        // to pierwsze to tez task, ustawiam task i puszczam process
        // wyciagam relsy
        // queue z taskami przetwarzania relsow, task ma path
        // indeks gdzie co bylo

        return [];
    }

}
