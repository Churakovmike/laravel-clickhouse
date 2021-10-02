<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database;

use ChurakovMike\LaravelClickHouse\Database\Client\HttpClient;
use ChurakovMike\LaravelClickHouse\Database\Enums\InputOutputFormat;
use ChurakovMike\LaravelClickHouse\Database\Exceptions\ConnectionException;
use ChurakovMike\LaravelClickHouse\Database\Query\Grammar;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Query\Expression;
use ChurakovMike\LaravelClickHouse\Database\Query\Builder as QueryBuilder;

class Connection extends \Illuminate\Database\Connection implements ConnectionResolverInterface
{
    private HttpClient $client;

    public function __construct(array $databaseConfig, $database = '', $tablePrefix = '', array $config = [])
    {
        $this->client = new HttpClient();

        $this->database = $databaseConfig['database'];

        $this->setDatabaseName($databaseConfig['database']);

        $this->useDefaultQueryGrammar();

        $this->useDefaultPostProcessor();
    }

    /**
     * @experimental
     */
    public function getDatabaseName(): string
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

    public function setOutputFormat(string $query, $format = InputOutputFormat::JSON): string
    {
        return $query . ' FORMAT ' . $format;
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

    public function cursor($query, $bindings = [], $useReadPdo = true): array
    {
        $statement = $this->bindQueryValues($query, $bindings);

        $statement = $this->setOutputFormat($statement);

        $result = $this->client->get($statement);

        return json_decode($result)->data;
    }

    public function insert($query, $bindings = [])
    {
        $statement = $this->bindQueryValues($query, $bindings);

        return $this->client->post($statement);
    }

    public function update($query, $bindings = [])
    {
        return $this->affectingStatement($query, $bindings);
    }

    public function delete($query, $bindings = [])
    {
        $statement = $this->bindQueryValues($query, $bindings);

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
        $statement = $this->bindQueryValues($query, $bindings);

        return $this->client->post($statement);
    }

    public function unprepared($query)
    {
        return $this->client->post($query);
    }

    /**
     * Use the replaceArray() method instead of this.
     *
     * @param array $bindings
     * @return array|void
     *
     * @deprecated
     */
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
    public function transaction(\Closure $callback, $attempts = 1): void
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    /**
     * @deprecated
     *
     * @throws ConnectionException
     */
    public function beginTransaction(): void
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    /**
     * @deprecated
     *
     * @throws ConnectionException
     */
    public function commit(): void
    {
        throw new ConnectionException('Clickhouse don\t support commit');
    }

    /**
     * @deprecated
     *
     * @param null $toLevel
     * @throws ConnectionException
     */
    public function rollBack($toLevel = null): void
    {
        throw new ConnectionException('Clickhouse don\t support rollbacks');
    }

    public function transactionLevel(): void
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    public function pretend(\Closure $callback)
    {
        // TODO: Implement pretend() method.
    }

    public function connection($name = null): self
    {
        return $this;
    }

    public function getDefaultConnection(): self
    {
        return $this->connection();
    }

    public function setDefaultConnection($name): bool
    {
        return true;
    }

    protected function getDefaultQueryGrammar(): Grammar
    {
        return new Grammar();
    }

    public function query(): QueryBuilder
    {
        return new QueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}
