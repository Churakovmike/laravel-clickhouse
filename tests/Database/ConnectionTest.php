<?php

namespace ChurakovMike\LaravelClickHouse\Tests\Database;

use ChurakovMike\LaravelClickHouse\Database\Client\HttpClient;
use ChurakovMike\LaravelClickHouse\Database\Connection;
use ChurakovMike\LaravelClickHouse\Database\Exceptions\ConnectionException;
use ChurakovMike\LaravelClickHouse\Database\Query\Builder;
use ChurakovMike\LaravelClickHouse\Tests\TestCase;
use Illuminate\Database\Query\Expression;
use ChurakovMike\LaravelClickHouse\Database\Query\Builder as QueryBuilder;

class ConnectionTest extends TestCase
{
    public function testGetConnection(): void
    {
        $connection = $this->getConnection();

        $this->assertInstanceOf(Connection::class, $connection);
    }

    public function testGetDatabaseName(): void
    {
        $connection = $this->getConnection();

        $this->assertEquals('test_database', $connection->getDatabaseName());
    }

    public function testTableFunction(): void
    {
        $connection = $this->getConnection();
        $builder = $connection->table('test_table');

        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertEquals('test_database.test_table', $builder->from);
    }

    public function testRawExpression(): void
    {
        $rawQuery = 'select * from test_database.test_table';
        $connection = $this->getConnection();
        $expression = $connection->raw($rawQuery);

        $this->assertInstanceOf(Expression::class, $expression);
        $this->assertEquals($rawQuery, $expression->getValue());
    }

    public function testSelectOne(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testSelect(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testBindQueryValues(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testSetOutputFormat(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testReplaceArray(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testCursor(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testInsert(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testUpdate(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testDelete(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testStatement(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testAffectingStatement(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testUnprepared(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testPrepareBindings(): void
    {
        $this->markTestSkipped('must be implemented.');
    }

    public function testTransaction(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->transaction(fn($closure) => $closure);
    }

    public function testBeginTransaction(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->beginTransaction();
    }

    public function testCommit(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->commit();
    }

    public function testRollBack(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->rollBack();
    }

    public function testTransactionLevel(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->transactionLevel();
    }

    public function testPretend(): void
    {
        $connection = $this->getConnection();
        $this->assertNull($connection->pretend(fn($closure) => $closure));
    }

    public function testConnection(): void
    {
        $connection = $this->getConnection();
        $conn = $connection->connection();
        $this->assertSame($connection, $conn);
    }

    public function testGetDefaultConnection(): void
    {
        $connection = $this->getConnection();
        $conn = $connection->getDefaultConnection();
        $this->assertSame($connection, $conn);
    }

    public function testSetDefaultConnection(): void
    {
        $connection = $this->getConnection();
        $result = $connection->setDefaultConnection('test-connection');
        $this->assertIsBool($result);
        $this->assertTrue($connection->setDefaultConnection($result));
    }

    public function testQuery(): void
    {
        $connection = $this->getConnection();
        $builder = $connection->query();

        $this->assertInstanceOf(QueryBuilder::class, $builder);
    }

    public function testGetClient(): void
    {
        $connectionMock = $this->getConnectionMock();
        $connectionMock->method('getClient')
            ->willReturn($this->createMock(HttpClient::class));

        $mock = $connectionMock->getClient([]);

        $this->assertTrue($mock instanceof HttpClient);
    }

    public function testGetDefaultQueryGrammar(): void
    {
        $this->markTestSkipped('must be implemented.');
    }
}
