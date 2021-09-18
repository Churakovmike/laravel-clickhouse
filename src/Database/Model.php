<?php

namespace ChurakovMike\LaravelClickHouse\Database;

class Model extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'events_local';

    protected $connection = 'clickhouse';

    public function setConnection($name)
    {
        $this->connection = 'clickhouse';

        return $this;
    }
}
