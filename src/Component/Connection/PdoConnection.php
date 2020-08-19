<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Component\Connection;

use Ulrack\Dbal\Common\ConnectionInterface;
use Ulrack\Dbal\Common\ParameterizedQueryComponentInterface;
use Ulrack\Dbal\Common\QueryInterface;
use Ulrack\Dbal\Common\QueryResultInterface;
use Ulrack\Dbal\Pdo\Component\Result\PdoQueryResult;
use PDO;
use RuntimeException;

class PdoConnection implements ConnectionInterface
{
    /**
     * Contains the database connection object.
     *
     * @var PDO
     */
    private $connection;

    /**
     * Constructor
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Begins a transaction.
     *
     * @return void
     */
    public function startTransaction(): void
    {
        if (!$this->connection->inTransaction()) {
            if (!$this->connection->beginTransaction()) {
                throw new RuntimeException(
                    'Could not start database transaction.'
                );
            }
        }
    }

    /**
     * Commits a transaction.
     *
     * @return void
     */
    public function commit(): void
    {
        if ($this->connection->inTransaction()) {
            if (!$this->connection->commit()) {
                throw new RuntimeException(
                    'Could not commit database transaction.'
                );
            }
        }
    }

    /**
     * Rolls a transaction back.
     *
     * @return void
     */
    public function rollback(): void
    {
        if ($this->connection->inTransaction()) {
            if (!$this->connection->rollBack()) {
                throw new RuntimeException(
                    'Could not roll back database transaction.'
                );
            }
        }
    }

    /**
     * Returns the ID of the last insert row.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Executes a query.
     *
     * @param QueryInterface $query
     *
     * @return QueryResultInterface
     */
    public function query(QueryInterface $query): QueryResultInterface
    {
        if ($query instanceof ParameterizedQueryComponentInterface) {
            $statement = $this->connection
                ->prepare($query->getQuery());
            $statement->execute($query->getParameters());

            return new PdoQueryResult($statement);
        }

        return new PdoQueryResult(
            $this->connection->query($query->getQuery())
        );
    }
}
