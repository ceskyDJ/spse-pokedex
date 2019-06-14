<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Exception;

/**
 * Exception for injecting to bad parameter (that doesn't need it)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Exceptions
 */
class NonInjectablePropertyException extends Exception
{
}