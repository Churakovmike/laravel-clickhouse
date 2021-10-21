<?php

namespace ChurakovMike\LaravelClickHouse\Tests;

use ChurakovMike\LaravelClickHouse\Database\Builder;

class ModelTest extends TestCase
{
    public function testSetConnection(): void
    {
        $connectionName = 'clickhouse';
        $model = $this->getModel()
            ->setConnection($connectionName);

        $this->assertEquals($connectionName, $model->getConnectionName());
    }

    public function testGetTable(): void
    {
        $tableName = 'test_database.models';
        $mock = $this->getModelMock();
        $mock
            ->method('getTable')
            ->willReturn($tableName);

        $this->assertEquals($tableName, $mock->getTable());
    }

    public function testSetTable(): void
    {
        $tableName = 'users';
        $model = $this
            ->getModel()
            ->setTable($tableName);

        $this->assertNotEquals($tableName, $model->getTable());
    }

    public function testGetConnectionResolver(): void
    {
        $this->assertTrue(true);
    }

    public function testNewModelQuery(): void
    {
        $model = $this->getModel();
        $model::setConnectionResolver($this->getConnection());

        $this->assertInstanceOf(Builder::class, $model->newModelQuery());
    }

    public function testNewEloquentBuilder(): void
    {
        $model = $this->getModel();
        $model::setConnectionResolver($this->getConnection());

        $this->assertInstanceOf(Builder::class, $model->newEloquentBuilder($model->getConnection()->query()));
    }
}
