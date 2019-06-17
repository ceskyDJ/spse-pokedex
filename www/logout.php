<?php

declare(strict_types = 1);

/**
 * Log-out person
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Models\PersonManager;

/**
 * @var PersonManager $personManager
 */
$personManager = $container->getInstance(PersonManager::class);

// Static data
/**
 * @var \App\Entity\Person $person
 */
$person = isset($_SESSION['person']) ? $_SESSION['person'] : null;
$isLoggedIn = $person !== null;

if($isLoggedIn === true) {
    $personManager->logout();
}

$router->route("login");