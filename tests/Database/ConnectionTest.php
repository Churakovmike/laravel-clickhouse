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
        $connection = $this->getConnectionMock();
        $connection
            ->method('selectOne')
            ->willReturn([]);

        $this->assertEquals([], $connection->selectOne('select * from table'));
    }

    public function testSelect(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('select')
            ->willReturn([]);

        $this->assertEquals([], $connection->select('select * from table'));
    }

    public function testBindQueryValues(): void
    {
        $connection = $this->getConnection();
        $statement = 'select * from table where id = ?';

        $this->assertEquals(
            'select * from table where id = 5',
            $connection->bindQueryValues($statement, [5])
        );
    }

    public function testSetOutputFormat(): void
    {
        $connection = $this->getConnection();
        $statement = 'select * from table';

        $this->assertEquals('select * from table FORMAT JSON', $connection->setOutputFormat($statement));
    }

    public function testReplaceArray(): void
    {
        $connection = $this->getConnection();
        $statement = 'select * from table where user_id = ?';

        $this->assertEquals(
            'select * from table where user_id = 5',
            $connection->replaceArray('?', [5], $statement)
        );
    }

    public function testCursor(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('cursor')
            ->willReturn([]);

        $this->assertEquals([], $connection->cursor('select * from table'));
    }

    public function testInsert(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('insert')
            ->willReturn(true);

        $this->assertEquals(true, $connection->insert(''));
    }

    public function testUpdate(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('update')
            ->willReturn(true);

        $this->assertEquals(true, $connection->update(''));
    }

    public function testDelete(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('delete')
            ->willReturn(true);

        $this->assertEquals(true, $connection->delete('delete * from table'));
    }

    public function testStatement(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('statement')
            ->willReturn([]);

        $this->assertEquals([], $connection->statement('select * from table'));
    }

    public function testAffectingStatement(): void
    {
        $connection = $this->getConnectionMock();
        $connection
            ->method('affectingStatement')
            ->willReturn(true);

        $this->assertEquals(true, $connection->affectingStatement('select * from table'));
    }

    public function testUnprepared(): void
    {
        $connectionMock = $this->getConnectionMock();
        $connectionMock
            ->method('getClient')
            ->willReturn($this->createMock(HttpClient::class));

        $connectionMock
            ->method('unprepared')
            ->willReturn(true);

        $this->assertEquals(true, $connectionMock->unprepared(''));
    }

    public function testPrepareBindings(): void
    {
        $bindings = ['test_bindings'];
        $connection = $this->getConnection();

        $this->assertEquals($bindings, $connection->prepareBindings($bindings));
    }

    public function testTransaction(): void
    {
        $connection = $this->getConnection();
        $this->expectException(ConnectionException::class);
        $connection->transaction(fn ($closure) => $closure);
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
        $this->assertNull($connection->pretend(fn ($closure) => $closure));
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
