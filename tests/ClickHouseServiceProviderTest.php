<?php

namespace ChurakovMike\LaravelClickHouse\Tests;

use ChurakovMike\LaravelClickHouse\ClickhouseServiceProvider;
use PHPUnit\Framework\MockObject\MockObject;

class ClickHouseServiceProviderTest extends TestCase
{
    public function testBoot(): void
    {
        /** @var MockObject|ClickhouseServiceProvider $mock */
        $mock = $this->createStub(ClickhouseServiceProvider::class);

        $this->assertEquals(null, $mock->register());
    }
}
