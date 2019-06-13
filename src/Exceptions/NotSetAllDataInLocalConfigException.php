<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Exception;

/**
 * Exception for missing override default values in local config file
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Exceptions
 */
class NotSetAllDataInLocalConfigException extends Exception
{
}