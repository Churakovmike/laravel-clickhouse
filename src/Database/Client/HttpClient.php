<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Database\Client;

use GuzzleHttp\Client;

class HttpClient
{
    private const DEFAULT_HOST = 'http://127.0.0.1';
    private const DEFAULT_PORT = 8123;
    private const DEFAULT_USERNAME = 'default';
    private const DEFAULT_PASSWORD = '';
    private const DEFAULT_TIMEOUT = 10;

    private ?Client $client = null;

    private int $port;

    private string $host;

    private string $username;

    private string $password;

    private int $timeout;

    public function __construct(
        string $host = self::DEFAULT_HOST,
        int $port = self::DEFAULT_PORT,
        string $username = self::DEFAULT_USERNAME,
        string $password = self::DEFAULT_PASSWORD,
        ?int $timeout = self::DEFAULT_TIMEOUT
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->timeout = $timeout;
        $this->initClient();
    }

    //todo:add response parsing
    public function get(string $query)
    {
        $response = $this->client->request('GET', '', [
            'query' => $this->getPreparedQuery($query),
        ]);

        return $response->getBody()->getContents();
    }

    public function post(string $query): bool
    {
        $response = $this->client->request('POST', '', [
            'query' => $this->getPreparedQuery($query),
        ]);

        return $response->getStatusCode() === 200;
    }

    protected function initClient(): void
    {
        $this->client = new Client($this->getConfig());
    }

    protected function getConfig(): array
    {
        return [
            'base_uri' => $this->host . ':' . $this->port,
            'timeout' => $this->timeout,
            'auth' => [
                $this->username,
                $this->password,
            ]
        ];
    }

    protected function getPreparedQuery(string $query): array
    {
        return [
            'query' => $query,
        ];
    }
}
