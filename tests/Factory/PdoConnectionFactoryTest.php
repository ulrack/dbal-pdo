<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Ulrack\Dbal\Pdo\Factory\PdoConnectionFactory;
use Ulrack\Dbal\Pdo\Exception\ConnectionException;
use Ulrack\Dbal\Pdo\Component\Connection\PdoConnection;

/**
 * @coversDefaultClass \Ulrack\Dbal\Pdo\Factory\PdoConnectionFactory
 * @covers \Ulrack\Dbal\Pdo\Exception\ConnectionException
 */
class PdoConnectionFactoryTest extends TestCase
{
    /**
     * @covers ::create
     *
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new PdoConnectionFactory();
        $this->assertInstanceOf(
            PdoConnection::class,
            $factory->create(
                'mysql:dbname=test;host=localhost',
                'test',
                'test'
            )
        );

        $this->expectException(ConnectionException::class);

        $factory->create('foo', 'bar', 'baz');
    }
}
