<?php

declare(strict_types = 1);

/**
 * Home
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Repository\DBPersonRepository;
use App\Repository\DBPokemonRepository;

/**
 * @var \App\Repository\DBPokemonRepository $dbPokemonRepository
 */
$dbPokemonRepository = $container->getInstance(DBPokemonRepository::class);
/**
 * @var \App\Repository\DBPersonRepository $dbPersonRepository
 */
$dbPersonRepository = $container->getInstance(DBPersonRepository::class);

// Static data
/**
 * @var \App\Entity\Person $person
 */
$person = isset($_SESSION['person']) ? $_SESSION['person'] : null;
$isLoggedIn = $person !== null;
$persons = $dbPersonRepository->getPersons();
$pokemons = $dbPokemonRepository->getPokemons();

if ($isLoggedIn === false) {
    $router->route("login");
}

if($person->isAdmin() === false) {
    $router->route("user");
}

require_once '../src/Templates/admin/index.phtml';