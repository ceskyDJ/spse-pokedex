<?php

declare(strict_types = 1);

/**
 * Select pokemon
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
if($_GET) {
    $pokemon = $dbPokemonRepository->getPokemonByName($_GET['name']);

    if ($pokemon !== null) {
        $pokemons = [$pokemon];
    } else {
        $pokemons = $dbPokemonRepository->getPokemons();
    }
} else {
    $pokemons = $dbPokemonRepository->getPokemons();
}

require_once '../src/Templates/select-pokemon.phtml';