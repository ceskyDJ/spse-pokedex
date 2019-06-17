<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Exception;

/**
 * Exception for entering invalid user or password for user authentication
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Exceptions
 */
class InvalidUserPasswordException extends Exception
{
}