<?php

declare(strict_types = 1);

namespace App\Utils;

use function explode;
use Olifolkerd\Convertor\Convertor;
use Olifolkerd\Convertor\Exceptions\ConvertorDifferentTypeException;
use Olifolkerd\Convertor\Exceptions\ConvertorException;
use Olifolkerd\Convertor\Exceptions\ConvertorInvalidUnitException;

/**
 * Helper for physical units counting
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Utils
 */
class PhysicalUnitsHelper
{
    /**
     * Converts metric units in input string to base metric unit
     *
     * @param string $input String any metric unit and its value
     *
     * @return float Value in meters
     */
    public function convertToBaseMetricUnit(string $input): float
    {
        return $this->convertToUnit($input, "m");
    }

    /**
     * Converts weight units in input string to base weight unit
     *
     * @param string $input String with any weight unit and its value
     *
     * @return float Value in kilograms
     */
    public function convertToBaseWeightUnit(string $input): float
    {
        return $this->convertToUnit($input, "kg");
    }

    /**
     * Converts to specific unit
     *
     * @param string $input String with value and unit
     * @param string $outputUnit Required unit
     *
     * @return float Result
     */
    public function convertToUnit(string $input, string $outputUnit): float
    {
        list($value, $unit) = explode(" ", $input);

        $converter = new Convertor($value, $outputUnit);
        try {
            return $converter->to($unit);
        } catch (ConvertorDifferentTypeException $e) {
            return 0.0;
        } catch (ConvertorException $e) {
            return 0.0;
        } catch (ConvertorInvalidUnitException $e) {
            return 0.0;
        }
    }
}