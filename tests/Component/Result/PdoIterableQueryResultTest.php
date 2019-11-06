<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Tests\Component\Result;

use PHPUnit\Framework\TestCase;
use Ulrack\Dbal\Pdo\Component\Result\PdoIterableQueryResult;
use PDOStatement;

/**
 * @coversDefaultClass \Ulrack\Dbal\Pdo\Component\Result\PdoIterableQueryResult
 */
class PdoIterableQueryResultTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::count
     * @covers ::fetchAll
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     *
     * @return void
     */
    public function testIterable(): void
    {
        $entryData = [
            'id' => '1',
            'foo' => 'bar'
        ];

        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->expects(static::once())
            ->method('rowCount')
            ->willReturn(3);

        $statementMock->expects(static::exactly(2))
            ->method('fetch')
            ->willReturn($entryData);

        $statementMock->expects(static::once())
            ->method('fetchAll')
            ->willReturn([$entryData]);

        $subject = new PdoIterableQueryResult($statementMock);

        $this->assertInstanceOf(
            PdoIterableQueryResult::class,
            $subject
        );

        $this->assertEquals(3, count($subject));

        foreach ($subject as $key => $entry) {
            $this->isType('int', $key);
            $this->assertEquals($entryData, $entry);

            if ($key === 1) {
                break;
            }
        }

        $this->assertEquals(
            [$entryData, $entryData, $entryData],
            $subject->fetchAll()
        );
    }
}
