<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Factory;

use Ulrack\Dbal\Pdo\Connection\PdoConnection;
use PDO;
use Throwable;
use RuntimeException;

class PdoConnectionFactory
{
    /**
     * Creates an instance of PdoConnection
     *
     * @param string      $dsn
     * @param string      $username
     * @param string|null $password
     * @param array       $options
     *
     * @return PdoConnection
     *
     * @throws RuntimeException When the PDO connection can not be established.
     */
    public function create(
        string $dsn,
        string $username,
        string $password = null,
        array $options = [],
        array $attributes = [PDO::ATTR_EMULATE_PREPARES => false]
    ): PdoConnection {
        try {
            $connection = new PDO($dsn, $username, $password, $options);
            foreach ($attributes as $attribute => $value) {
                $connection->setAttribute($attribute, $value);
            }

            return new PdoConnection($connection);
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Could not connect to database.',
                (int) $exception->getCode()
            );
        }
    }
}
