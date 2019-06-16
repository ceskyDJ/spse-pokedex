<?php

declare(strict_types = 1);

/**
 * Remove pokemon
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
use App\Exceptions\InsufficientPermissionsException;
use App\Model\PokemonManager;

/**
 * @var \App\Model\PokemonManager $pokemonManager
 */
$pokemonManager = $container->getInstance(PokemonManager::class);

// Static data
/**
 * @var \App\Entity\Person $person
 */
$person = isset($_SESSION['person']) ? $_SESSION['person'] : null;
$isLoggedIn = $person !== null;

if ($isLoggedIn === false) {
    $router->route("login");
}

if ($person->isAdmin() === false) {
    $router->route("user");
}

if ($_GET) {
    try {
        $pokemonManager->remove((int)$_GET['id']);

        $router->route("admin");
    } catch (BadFormDataException $e) {
        $router->route("admin");
    } catch (InsufficientPermissionsException $e) {
        $router->route("user");
    }
} else {
    $router->route("admin");
}