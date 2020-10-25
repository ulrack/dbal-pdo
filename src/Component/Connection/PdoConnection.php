<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Component\Connection;

use PDO;
use RuntimeException;
use Ulrack\Dbal\Common\QueryInterface;
use Ulrack\Dbal\Common\ConnectionInterface;
use Ulrack\Dbal\Common\QueryResultInterface;
use Ulrack\Dbal\Pdo\Exception\QueryException;
use Ulrack\Dbal\Pdo\Component\Result\PdoQueryResult;
use Ulrack\Dbal\Common\ParameterizedQueryComponentInterface;

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
     *
     * @throws QueryException When the query has an execution error.
     */
    public function query(QueryInterface $query): QueryResultInterface
    {
        if ($query instanceof ParameterizedQueryComponentInterface) {
            $statement = $this->connection
                ->prepare($query->getQuery());
            $statement->execute($query->getParameters());

            $result = $statement;
        } else {
            $result = $this->connection->query($query->getQuery());
        }

        if ($result === false) {
            throw new QueryException(
                $query->getQuery(),
                $this->connection->errorCode() ?? 'unknown',
                $this->connection->errorInfo() ?? ['unknown']
            );
        }

        return new PdoQueryResult($result);
    }
}
