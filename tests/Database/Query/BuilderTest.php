<?php

namespace ChurakovMike\LaravelClickHouse\Tests\Query;

use ChurakovMike\LaravelClickHouse\Tests\TestCase;
use ChurakovMike\LaravelClickHouse\Database\Query\Builder;
use ChurakovMike\LaravelClickHouse\Database\Query\Grammar;

class BuilderTest extends TestCase
{
    public function testUpdate(): void
    {
        $builder = new Builder(
            $this->getConnectionMock(),
            new Grammar()
        );

        $this->assertEquals(false, $builder->update([]));
    }
}
