<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database\Query;

use Illuminate\Database\Query\Builder;

class Grammar extends \Illuminate\Database\Query\Grammars\Grammar
{
    public function compileDelete(Builder $query): string
    {
        $table = $this->wrapTable($query->from);

        $where = $this->compileWheres($query);

        return trim(
            isset($query->joins)
                ? $this->compileDeleteWithJoins($query, $table, $where)
                : $this->compileDeleteWithoutJoins($query, $table, $where)
        );
    }

    protected function compileDeleteWithoutJoins(Builder $query, $table, $where): string
    {
        return "alter table {$table} delete {$where}";
    }

    /**
     * @deprecated
     * @todo: Fix this method.
     *
     * @param Builder $query
     * @param string $table
     * @param string $where
     * @return string
     */
    protected function compileDeleteWithJoins(Builder $query, $table, $where): string
    {
        $alias = last(explode(' as ', $table));

        $joins = $this->compileJoins($query, $query->joins);

        return "delete {$alias} from {$table} {$joins} {$where}";
    }
}
