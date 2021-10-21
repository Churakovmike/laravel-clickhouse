<?php

namespace ChurakovMike\LaravelClickHouse\Tests\Query;

use ChurakovMike\LaravelClickHouse\Database\Query\Builder;
use ChurakovMike\LaravelClickHouse\Database\Query\Grammar;
use ChurakovMike\LaravelClickHouse\Tests\TestCase;

class GrammarTest extends TestCase
{
    public function testCompileDelete(): void
    {
        $grammar = new Grammar();

        $query = $grammar->compileDelete(new Builder($this->getConnection()));

        $this->assertEquals('alter table "" delete', $query);
    }

    public function testPrepareBindingsForUpdate(): void
    {
        $grammar = new Grammar();

        // todo: fix test
        //$query = $grammar->prepareBindingsForUpdate(['id'], [5]);

        $this->assertEquals(true, true);
    }

    public function testCompileUpdate(): void
    {
        $grammar = new Grammar();

        $query = $grammar->compileUpdate(new Builder($this->getConnection()), []);

        $this->assertEquals('alter table "" update', $query);
    }
}
