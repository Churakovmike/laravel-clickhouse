<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

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

    public function prepareBindingsForUpdate(array $bindings, array $values): array
    {
        $cleanBindings = Arr::except($bindings, ['select', 'join']);

        return array_values(
            array_merge($bindings['join'], $values, Arr::flatten($cleanBindings))
        );
    }

    public function compileUpdate(Builder $query, array $values): string
    {
        $table = $this->wrapTable($query->from);

        $columns = $this->compileUpdateColumns($query, $values);

        $where = $this->compileWheres($query);

        return trim(
            isset($query->joins)
                ? $this->compileUpdateWithJoins($query, $table, $columns, $where)
                : $this->compileUpdateWithoutJoins($query, $table, $columns, $where)
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

    protected function compileUpdateWithoutJoins(Builder $query, $table, $columns, $where): string
    {
//        dd($table, $columns, $where);

        return "update {$table} set {$columns} {$where}";
    }
}
