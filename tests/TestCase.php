<?php

declare(strict_types=1);

namespace ChurakovMike\LaravelClickHouse\Tests;

use ChurakovMike\LaravelClickHouse\Database\Connection;
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
}
