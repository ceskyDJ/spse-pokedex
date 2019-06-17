<?php

declare(strict_types = 1);

/**
 * Add user
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
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

if ($isLoggedIn === false) {
    $router->route("login");
}

if ($person->isAdmin() === false) {
    $router->route("user");
}

if ($_POST) {
    try {
        $personManager->register(
            $_POST['nick'],
            $_POST['password'],
            $_POST['password-again'],
            $_POST['email'],
            $_POST['first-name'],
            $_POST['last-name'],
            $_POST['birth']
        );

        $router->route("admin");
    } catch (BadFormDataException $e) {
        $message = $e->getMessage();
    }
}

require_once '../src/Templates/admin/add-user.phtml';