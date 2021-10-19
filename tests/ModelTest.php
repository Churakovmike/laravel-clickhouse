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
        $tableName = 'models';
        $model = $this
            ->getModel()
            ->setTable($tableName);

        $this->assertEquals($tableName, $model->getTable());
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
        $this->markTestSkipped();
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
