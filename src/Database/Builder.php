<?php

namespace ChurakovMike\LaravelClickHouse\Database;

use ChurakovMike\LaravelClickHouse\Database\Query\Grammar;
use Illuminate\Database\ConnectionInterface;

class Builder
{
    private ConnectionInterface $connection;
    private Grammar $grammar;

    public function __construct(ConnectionInterface $connection, Grammar $grammar)
    {
        $this->connection = $connection;
        $this->grammar = $grammar;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function getGrammar(): Grammar
    {
        return $this->grammar;
    }
}
