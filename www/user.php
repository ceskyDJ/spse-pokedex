<?php

declare(strict_types = 1);

/**
 * User
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Repository\DBPokemonRepository;
use App\Repository\DBTypeRepository;

/**
 * @var \App\Repository\DBPokemonRepository $dbPokemonRepository
 */
$dbPokemonRepository = $container->getInstance(DBPokemonRepository::class);
/**
 * @var \App\Repository\DBTypeRepository $dbTypeRepository
 */
$dbTypeRepository = $container->getInstance(DBTypeRepository::class);

// Static data
/**
 * @var \App\Entity\Person $person
 */
$person = isset($_SESSION['person']) ? $_SESSION['person'] : null;
$isLoggedIn = $person !== null;
$types = $dbTypeRepository->getTypes();

if ($isLoggedIn === false) {
    $router->route("login");
}

// Dynamic data
if ($_GET) {
    $type = $dbTypeRepository->getTypeById((int)$_GET['type']);

    if ($type !== null) {
        $pokemons = $dbPokemonRepository->getPokemonsByTypeAndOwner($type, $person);
    } else {
        $pokemons = $dbPokemonRepository->getPokemonsByOwner($person);
    }
} else {
    $pokemons = $dbPokemonRepository->getPokemonsByOwner($person);
}

require_once '../src/Templates/user.phtml';