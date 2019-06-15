<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Exception;

/**
 * Exception for trying perform action without sufficient permissions
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Exceptions
 */
class InsufficientPermissionsException extends Exception
{
}