<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database;

use ChurakovMike\ClickHouseClient\HttpClient;
use ChurakovMike\LaravelClickHouse\Database\Enums\InputOutputFormat;
use ChurakovMike\LaravelClickHouse\Database\Exceptions\ConnectionException;
use ChurakovMike\LaravelClickHouse\Database\Query\Grammar;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Query\Expression;

class Connection extends \Illuminate\Database\Connection implements ConnectionResolverInterface
{
    private HttpClient $client;

    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
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
        return $this->database;
    }

    public function table($table, $as = null)
    {
        return $this->query()->from($this->database . '.' . $table, $as);
    }

    public function raw($value)
    {
        return new Expression($value);
    }

    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement selectOne() method.
    }

    public function select($query, $bindings = [], $useReadPdo = false)
    {
        if ($this->pretending()) {
            return [];
        }

        $statement = $this->bindQueryValues($query, $bindings);

        $statement = $this->setOutputFormat($statement);

        $result = $this->client->get($statement);

        return json_decode($result)->data;
    }

    public function bindQueryValues(string $statement, array $bindings): string
    {
        return $this->replaceArray('?', $bindings, $statement);
    }

    public function setOutputFormat(string $query): string
    {
        return $query . ' FORMAT ' . InputOutputFormat::JSON;
    }

    public function replaceArray($search, array $replace, $subject): string
    {
        $segments = explode($search, $subject);

        $result = array_shift($segments);

        foreach ($segments as $segment) {

            $replaceItem = array_shift($replace) ?? $search;

            if (is_string($replaceItem)) {
                $replaceItem = "'" . $replaceItem . "'";
            }

            $result .= $replaceItem . $segment;
        }

        return $result;
    }

    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        // TODO: Implement cursor() method.
    }

    public function insert($query, $bindings = [])
    {
        $statement = $this->bindQueryValues($query, $bindings);

        return $this->client->post($statement);
    }

    public function update($query, $bindings = [])
    {
        // TODO: Implement update() method.
    }

    public function delete($query, $bindings = [])
    {
        $statement = $this->bindQueryValues($query, $bindings);

//        $statement = $this->setOutputFormat($statement);
//        dd($statement);
        $this->client->post($statement);
    }

    //@todo: add get/post to save/insert
    public function statement($query, $bindings = [])
    {
        $statement = $this->bindQueryValues($query, $bindings);

        $this->recordsHaveBeenModified();

        return $this->client->get($statement);
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

    /**
     * @deprecated
     *
     * @param \Closure $callback
     * @param int $attempts
     * @return mixed|void
     * @throws ConnectionException
     */
    public function transaction(\Closure $callback, $attempts = 1)
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    /**
     * @deprecated
     *
     * @throws ConnectionException
     */
    public function beginTransaction()
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    /**
     * @deprecated
     *
     * @throws ConnectionException
     */
    public function commit()
    {
        throw new ConnectionException('Clickhouse don\t support commit');
    }

    /**
     * @deprecated
     *
     * @param null $toLevel
     * @throws ConnectionException
     */
    public function rollBack($toLevel = null)
    {
        throw new ConnectionException('Clickhouse don\t support rollbacks');
    }

    public function transactionLevel()
    {
        // TODO: Implement transactionLevel() method.
    }

    public function pretend(\Closure $callback)
    {
        // TODO: Implement pretend() method.
    }

    public function connection($name = null)
    {
        return $this;
    }

    public function getDefaultConnection()
    {
        return $this->connection();
    }

    public function setDefaultConnection($name)
    {
        // TODO: Implement setDefaultConnection() method.
    }

    protected function getDefaultQueryGrammar(): Grammar
    {
        return new Grammar();
    }
}
