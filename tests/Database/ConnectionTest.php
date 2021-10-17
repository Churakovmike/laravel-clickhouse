<?php

namespace ChurakovMike\LaravelClickHouse\Tests\Database;

use ChurakovMike\LaravelClickHouse\Database\Connection;
use ChurakovMike\LaravelClickHouse\Database\Query\Builder;
use ChurakovMike\LaravelClickHouse\Tests\TestCase;
use Illuminate\Database\Query\Expression;

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
        //todo
    }

    public function testSelect(): void
    {
        //todo
    }

    public function testBindQueryValues(): void
    {
        //todo
    }

    public function testSetOutputFormat(): void
    {
        //todo
    }

    public function testReplaceArray(): void
    {
        //todo
    }

    public function testCursor(): void
    {
        //todo
    }

    public function testInsert(): void
    {
        //todo
    }

    public function testUpdate(): void
    {
        //todo
    }

    public function testDelete(): void
    {
        //todo
    }

    public function testStatement(): void
    {
        //todo
    }

    public function testAffectingStatement(): void
    {
        //todo
    }

    public function testUnprepared(): void
    {
        //todo
    }

    public function testPrepareBindings(): void
    {
        //todo
    }

    public function testTransaction(): void
    {
        //todo
    }

    public function testBeginTransaction(): void
    {
        //todo
    }

    public function testCommit(): void
    {
        //todo
    }

    public function testRollBack(): void
    {
        //todo
    }

    public function testTransactionLevel(): void
    {
        //todo
    }

    public function testPretend(): void
    {
        //todo
    }

    public function testConnection(): void
    {
        //todo
    }

    public function testGetDefaultConnection(): void
    {
        //todo
    }

    public function testSetDefaultConnection(): void
    {
        //todo
    }

    public function testQuery(): void
    {
        //todo
    }

    public function testGetClient(): void
    {
        //todo
    }

    public function testGetDefaultQueryGrammar(): void
    {
        //todo
    }
}
