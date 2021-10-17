<?php

namespace ChurakovMike\LaravelClickHouse\Tests\Database;

use ChurakovMike\LaravelClickHouse\Tests\TestCase;

class Connection extends TestCase
{
    public function testTest(): void
    {
        $this->assertEquals(1, 1);
    }
}
