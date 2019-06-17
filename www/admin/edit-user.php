<?php

declare(strict_types = 1);

/**
 * Edit user
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
use App\Exceptions\InsufficientPermissionsException;
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

if ($_GET) {
    if($_POST) {
        try {
            $personManager->setNewPassword((int)$_GET['id'], $_POST['new-password']);

            $router->route("admin");
        } catch (BadFormDataException $e) {
            $message = $e->getMessage();
        } catch (InsufficientPermissionsException $e) {
            $router->route("user");
        }
    }
} else {
    $router->route("admin");
}

require_once '../src/Templates/admin/edit-user.phtml';