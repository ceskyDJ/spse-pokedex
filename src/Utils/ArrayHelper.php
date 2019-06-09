<?php

declare(strict_types = 1);

namespace App\Utils;

use function array_filter;
use function array_keys;

/**
 * Helper for working with arrays (superstructure above PHP build in functions)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Utils
 */
class ArrayHelper
{
    /**
     * Removes keys without value (or with null value) from array
     *
     * @param array $inputArray Input array
     *
     * @return array Output array
     */
    public function removeEmptyKeys(array $inputArray): array
    {
        return array_filter($inputArray, "strlen");
    }

    /**
     * Removes items by name from array
     *
     * @param array $inputArray Input array
     * @param mixed $value Value for removing
     * @param bool $strict Strict comparing values
     *
     * @return array Output array
     */
    public function removeByValue(array $inputArray, $value, $strict = false): array
    {
        $keysWithValue = array_keys($inputArray, $value, $strict);

        foreach($keysWithValue as $key) {
            unset($inputArray[$key]);
        }

        return $inputArray;
    }
}