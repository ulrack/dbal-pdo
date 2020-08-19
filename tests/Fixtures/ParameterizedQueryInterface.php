<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Dbal\Pdo\Tests\Fixtures;

use Ulrack\Dbal\Common\QueryInterface;
use Ulrack\Dbal\Common\ParameterizedQueryComponentInterface;

interface ParameterizedQueryInterface extends QueryInterface, ParameterizedQueryComponentInterface
{

}
