<?php

namespace ChurakovMike\LaravelClickHouse\Database;

abstract class Model
{
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }
}
