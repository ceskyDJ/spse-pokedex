<?php

declare(strict_types = 1);

/**
 * Login
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
use App\Exceptions\InvalidUserPasswordException;
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
$message = "";

if($isLoggedIn === true) {
    $router->route("user");
}

// Dynamic data
if ($_POST) {
    try {
        $personManager->login($_POST['nick'], $_POST['password']);

        $router->route("user");
    } catch (BadFormDataException|InvalidUserPasswordException  $e) {
        $message = $e->getMessage();
    }
}

require_once '../src/Templates/login.phtml';