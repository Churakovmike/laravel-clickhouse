<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public $incrementing = false;

    public $timestamps = false;

    public function setConnection($name): self
    {
        $this->connection = $name;

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

    public function newModelQuery(): Builder
    {
        return $this->newEloquentBuilder(
            $this->newBaseQueryBuilder()
        )->setModel($this);
    }

    public function newEloquentBuilder($query): Builder
    {
        return new \ChurakovMike\LaravelClickHouse\Database\Builder($query);
    }

    protected function setKeysForSaveQuery(Builder $query): Builder
    {
        $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());

        return $query;
    }

    protected function performUpdate(Builder $query): bool
    {
        if ($this->fireModelEvent('updating') === false) {
            return false;
        }

        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            $this->saveDirtyKeyNameForSaveQuery($query, $dirty)->update($dirty);

            $this->syncChanges();

            $this->fireModelEvent('updated', false);
        }

        return true;
    }

    protected function saveDirtyKeyNameForSaveQuery(Builder $query, array $dirty): Builder
    {
        foreach ($dirty as $key => $value) {
            $query->where($key, '=', $this->getOriginal($key));
        }

        return $query;
    }
}
