<?php

declare(strict_types = 1);

namespace App\Utils;

use function hash;
use const PASSWORD_DEFAULT;
use function password_hash;
use function password_verify;

/**
 * Helper for cryptography things
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Utils
 */
class CryptographyHelper
{
    /**
     * Creates password hash
     *
     * @param string $password Raw password
     * @param string $userName Name of user that has the password
     *
     * @return string Password hash
     */
    public function hashPassword(string $password, string $userName): string
    {
        return password_hash($this->preparePassword($password, $userName), PASSWORD_DEFAULT);
    }

    /**
     * Verifies password for specific user from saved hash
     *
     * @param string $password Raw password
     * @param string $userName Name of user that has the password
     * @param string $hash Hash created from password after registration
     *
     * @return bool Is the password right?
     */
    public function verifyPassword(string $password, string $userName, string $hash): bool
    {
        return password_verify($this->preparePassword($password, $userName), $hash);
    }

    /**
     * Prepares password for hashing (adds some pepper and salt)
     *
     * @param string $password Raw password
     * @param string $userName Name of user that has the password
     *
     * @return string Prepared password
     */
    private function preparePassword(string $password, string $userName): string
    {
        $ownPepperFisrt = ")XwBm4`;";
        $ownPepperSecond = "|oZrA9o-";

        return hash("sha512", $ownPepperFisrt.$password.$userName.$ownPepperSecond);
    }
}