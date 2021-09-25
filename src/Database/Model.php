<?php

namespace ChurakovMike\LaravelClickHouse\Database;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Str;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function setConnection($name): self
    {
        $this->connection = self::getConnectionResolver()->getDefaultConnection();

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

    public function setTable($table): self
    {
        return $this;
    }

    public static function getConnectionResolver(): ConnectionResolverInterface
    {
        return static::$resolver;
    }
}