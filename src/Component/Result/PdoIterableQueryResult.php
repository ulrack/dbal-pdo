<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Component\Result;

use Iterator;
use Countable;
use PDOStatement;

class PdoIterableQueryResult implements Iterator, Countable
{
    /**
     * Contains the PDOStatement object.
     *
     * @var PDOStatement
     */
    private $statement;

    /**
     * Stores the results of the query iteration.
     *
     * @var array
     */
    private $resultStore = [];

    /**
     * Contains the count of the amount of entries.
     *
     * @var int
     */
    private $entryCount;

    /**
     * Contains the current iterator position.
     *
     * @var int
     */
    private $currentPosition = 0;

    /**
     * Constructor
     *
     * @param  PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
    {
        $this->statement  = $statement;
        $this->entryCount = $statement->rowCount();
    }

    /**
     * Returns the amount of entries in the iterator.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->entryCount;
    }

    /**
     * Fetches all rows from the result set.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        if (count($this->resultStore) < $this->entryCount) {
            $this->resultStore = array_merge(
                $this->resultStore,
                $this->statement->fetchAll()
            );
        }

        return $this->resultStore;
    }

    /**
     * Returns the value of the current iteration position.
     *
     * @return mixed
     */
    public function current()
    {
        if (!isset($this->resultStore[$this->currentPosition])) {
            $this->resultStore[] = $this->statement->fetch();
        }

        return $this->resultStore[$this->currentPosition];
    }

    /**
     * Retrieves the current key.
     *
     * @return int
     */
    public function key() : int
    {
        return $this->currentPosition;
    }

    /**
     * Sets the current position to the next position.
     *
     * @return void
     */
    public function next(): void
    {
        $this->currentPosition++;
    }

    /**
     * Resets the pointer.
     *
     * @return void
     */
    public function rewind() : void
    {
        $this->currentPosition = 0;
    }

    /**
     * Returns whether the current position is valid.
     *
     * @return bool
     */
    public function valid() : bool
    {
        return $this->currentPosition < $this->entryCount;
    }
}
