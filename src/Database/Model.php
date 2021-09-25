<?php

namespace ChurakovMike\LaravelClickHouse\Database;

use Illuminate\Support\Str;

class Model extends \Illuminate\Database\Eloquent\Model
{
//    protected $table = 'test_database.events_local';

    protected $connection = 'clickhouse';

    public function setConnection($name)
    {
        $this->connection = 'clickhouse';

        return $this;
    }

    public function getTable(): string
    {
        $tableName = $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));

        try {
           $databaseName = self::getConnectionResolver()->getDatabaseName();

           return $databaseName . '.' . $tableName;
        } catch (\Throwable $exception) {
            return $tableName;
        }
    }

    public function setTable($table)
    {
        return $this;

        $this->table = $table;

        return $this;
    }

    public static function getConnectionResolver()
    {
        return static::$resolver;
    }
}
