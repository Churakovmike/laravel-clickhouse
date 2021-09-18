<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database;

use ChurakovMike\ClickHouseClient\HttpClient;
use Illuminate\Support\Str;

class Connection extends \Illuminate\Database\Connection
{
    private HttpClient $client;

    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
//        dd($config, $pdo, $database, $tablePrefix);
        $this->client = new HttpClient();

        $this->database = $pdo['database'];

        $this->setDatabaseName($pdo['database']);

        $this->useDefaultQueryGrammar();

        $this->useDefaultPostProcessor();
    }

    /**
     * @experimental
     */
    public function getDatabaseName()
    {
        return 'test_database';
    }

    public function table($table, $as = null)
    {
        // TODO: Implement table() method.
    }

    public function raw($value)
    {
        // TODO: Implement raw() method.
    }

    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement selectOne() method.
    }

    public function select($query, $bindings = [], $useReadPdo = false)
    {
//        return $this->run($query, $bindings, function ($query, $bindings) use ($useReadPdo) {
        if ($this->pretending()) {
            return [];
        }

        $statement = $this->bindQueryValues($query, $bindings);

        $this->client->get($statement);
        dd($this->client->get($statement));

        //DB::statement( 'ALTER TABLE TEST_TABLE AUTO_INCREMENT=:incrementStart', ['incrementStart' => 1111] );
//        dd($statement);
        return $statement->fetchAll();
//        });
    }

    public function bindQueryValues(string $statement, array $bindings): string
    {
        return Str::replaceArray('?', $bindings, $statement);
    }

    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement cursor() method.
    }

    public function insert($query, $bindings = [])
    {
        // TODO: Implement insert() method.
    }

    public function update($query, $bindings = [])
    {
        // TODO: Implement update() method.
    }

    public function delete($query, $bindings = [])
    {
        // TODO: Implement delete() method.
    }

    public function statement($query, $bindings = [])
    {
        // TODO: Implement statement() method.
    }

    public function affectingStatement($query, $bindings = [])
    {
        // TODO: Implement affectingStatement() method.
    }

    public function unprepared($query)
    {
        // TODO: Implement unprepared() method.
    }

    public function prepareBindings(array $bindings)
    {
        // TODO: Implement prepareBindings() method.
    }

    public function transaction(\Closure $callback, $attempts = 1)
    {
        // TODO: Implement transaction() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollBack($toLevel = null)
    {
        // TODO: Implement commit() method.
    }

    public function transactionLevel()
    {
        // TODO: Implement transactionLevel() method.
    }

    public function pretend(\Closure $callback)
    {
        // TODO: Implement pretend() method.
    }
}
