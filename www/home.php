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
use App\Repository\DBTypeRepository;

/**
 * @var \App\Repository\DBPokemonRepository $dbPokemonRepository
 */
$dbPokemonRepository = $container->getInstance(DBPokemonRepository::class);
/**
 * @var \App\Repository\DBTypeRepository $dbTypeRepository
 */
$dbTypeRepository = $container->getInstance(DBTypeRepository::class);
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
$types = $dbTypeRepository->getTypes();

// Dynamic data
if ($_GET) {
    $type = $dbTypeRepository->getTypeById((int)$_GET['type']);
    $owner = $dbPersonRepository->getPersonByNick($_GET['owner']);

    if ($type !== null && $owner !== null) {
        $pokemons = $dbPokemonRepository->getPokemonsByTypeAndOwner($type, $owner);
    } elseif ($type !== null) {
        $pokemons = $dbPokemonRepository->getPokemonsByType($type);
    } elseif ($owner !== null) {
        $pokemons = $dbPokemonRepository->getPokemonsByOwner($owner);
    } else {
        $pokemons = $dbPokemonRepository->getPokemons();
    }
} else {
    $pokemons = $dbPokemonRepository->getPokemons();
}

require_once '../src/Templates/index.phtml';