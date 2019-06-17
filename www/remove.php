<?php

declare(strict_types = 1);

/**
 * Remove pokemon
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
use App\Model\PokemonManager;
use App\Repository\DBPokemonRepository;

/**
 * @var \App\Repository\DBPokemonRepository $dbPokemonRepository
 */
$dbPokemonRepository = $container->getInstance(DBPokemonRepository::class);
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

if ($_GET) {
    if (isset($_GET['id'])) {
        try {
            $pokemonManager->removeFromPerson((int)$_GET['id'], $person->getId());

            $router->route("user");
        } catch (BadFormDataException $e) {
            $router->route("user");
        }
    }
} else {
    $router->route("select-pokemon");
}