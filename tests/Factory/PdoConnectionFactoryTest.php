<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Ulrack\Dbal\Pdo\Factory\PdoConnectionFactory;
use Ulrack\Dbal\Pdo\Connection\PdoConnection;
use RuntimeException;

/**
 * @coversDefaultClass \Ulrack\Dbal\Pdo\Factory\PdoConnectionFactory
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

        $this->expectException(RuntimeException::class);

        $factory->create('foo', 'bar', 'baz');
    }
}
