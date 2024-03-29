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

    public function __construct(array $config)
    {
        $this->client = $this->getClient($config);

        $this->database = $config['database'];

        $this->setDatabaseName($config['database']);

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

    public function raw($value): Expression
    {
        return new Expression($value);
    }

    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        $records = $this->select($query, $bindings);

        return array_shift($records);
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

    public function update($query, $bindings = []): bool
    {
        return $this->affectingStatement($query, $bindings);
    }

    /**
     * @param string $query
     * @param array $bindings
     * @return int|void
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
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

    public function affectingStatement($query, $bindings = []): bool
    {
        $statement = $this->bindQueryValues($query, $bindings);

        return $this->client->post($statement);
    }

    public function unprepared($query)
    {
        return $this->client->post($query);
    }

    public function prepareBindings(array $bindings): array
    {
        return $bindings;
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
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function rollBack($toLevel = null): void
    {
        throw new ConnectionException('Clickhouse don\t support rollbacks');
    }

    /**
     * @deprecated
     *
     * @throws ConnectionException
     */
    public function transactionLevel(): void
    {
        throw new ConnectionException('Clickhouse don\t support transactions');
    }

    /**
     * @deprecated
     *
     * @param \Closure $callback
     * @return array|void
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function pretend(\Closure $callback)
    {
        //
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

    public function query(): QueryBuilder
    {
        return new QueryBuilder(
            $this,
            $this->getQueryGrammar(),
            $this->getPostProcessor()
        );
    }

    public function getClient(array $config): HttpClient
    {
        return new HttpClient(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['options']
        );
    }

    protected function getDefaultQueryGrammar(): Grammar
    {
        return new Grammar();
    }
}
