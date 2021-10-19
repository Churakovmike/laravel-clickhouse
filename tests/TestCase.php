<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Tests;

use ChurakovMike\LaravelClickHouse\Database\Connection;
use ChurakovMike\LaravelClickHouse\Database\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function getConnection(): Connection
    {
        return new Connection([
            'driver' => 'clickhouse',
            'host' => 'http://127.0.0.1',
            'port' => 8123,
            'database' => 'test_database',
            'username' => 'default',
            'password' => '',
            'options' => [
                'timeout' => 10,
            ],
        ]);
    }

    /**
     * @return MockObject|Connection
     */
    public function getConnectionMock(): MockObject
    {
        return $this->createMock(Connection::class);
    }

    public function getModel(): Model
    {
        return new Model();
    }
}
