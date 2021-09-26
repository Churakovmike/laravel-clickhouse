<?php

namespace ChurakovMike\LaravelClickHouse\Database\Query;

class Builder extends \Illuminate\Database\Query\Builder
{
    public function update(array $values)
    {
        $sql = $this->grammar->compileUpdate($this, $values);

        return $this->connection->update($sql, $this->cleanBindings(
            $this->grammar->prepareBindingsForUpdate($this->bindings, $values)
        ));
    }
}
