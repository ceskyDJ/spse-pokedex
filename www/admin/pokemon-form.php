<?php

declare(strict_types = 1);

/**
 * Pokemon form
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\BadFormDataException;
use App\Exceptions\InsufficientPermissionsException;
use App\Model\PokemonManager;
use App\Repository\DBCandyRepository;
use App\Repository\DBPokemonRepository;
use App\Repository\DBTypeRepository;

/**
 * @var \App\Model\PokemonManager $pokemonManager
 */
$pokemonManager = $container->getInstance(PokemonManager::class);
/**
 * @var \App\Repository\DBTypeRepository $dbTypeRepository
 */
$dbTypeRepository = $container->getInstance(DBTypeRepository::class);
/**
 * @var \App\Repository\DBCandyRepository $dbCandyRepository
 */
$dbCandyRepository = $container->getInstance(DBCandyRepository::class);
/**
 * @var \App\Repository\DBPokemonRepository $dbPokemonRepository
 */
$dbPokemonRepository = $container->getInstance(DBPokemonRepository::class);

// Static data
/**
 * @var \App\Entity\Person $person
 */
$person = isset($_SESSION['person']) ? $_SESSION['person'] : null;
$isLoggedIn = $person !== null;
$types = $dbTypeRepository->getTypes();
$candies = $dbCandyRepository->getCandies();
$pokemons = $dbPokemonRepository->getPokemons();
$message = "";
/**
 * @var \App\Entity\Pokemon $editedPokemon
 */
$editedPokemon = null;
$editedPokemonTypes = null;
$editedPokemonWeaknesses = null;

if ($isLoggedIn === false) {
    $router->route("login");
}

if ($person->isAdmin() === false) {
    $router->route("user");
}

if ($_GET) {
    // Edit
    $editedPokemon = $dbPokemonRepository->getPokemonById((int)$_GET['id']);

    $editedPokemonTypes = [];
    foreach ($editedPokemon->getTypes() as $type) {
        $editedPokemonTypes[] = $type->getId();
    }

    $editedPokemonWeaknesses = [];
    foreach ($editedPokemon->getWeaknesses() as $weakness) {
        $editedPokemonWeaknesses[] = $weakness->getId();
    }

    if ($_POST) {
        try {
            $pokemonManager->edit(
                (int)$_GET['id'],
                $_POST['official-number'],
                $_POST['name'],
                $_POST['image-url'],
                (float)$_POST['height'],
                (float)$_POST['weight'],
                (int)$_POST['candy'],
                (int)$_POST['required-candy-count'],
                (int)$_POST['egg-travel-length'],
                (float)$_POST['spawn-chance'],
                $_POST['spawn-time'],
                (float)$_POST['minimum-multiplier'],
                (float)$_POST['maximum-multiplier'],
                (int)$_POST['previous-evolution'],
                (int)$_POST['next-evolution'],
                $_POST['types'],
                $_POST['weaknesses']
            );

            $router->route("admin");
        } catch (BadFormDataException $e) {
            $message = $e->getMessage();
        } catch (InsufficientPermissionsException $e) {
            $router->route("user");
        }
    }
} else {
    // Add
    if ($_POST) {
        try {
            $pokemonManager->add(
                $_POST['official-number'],
                $_POST['name'],
                $_POST['image-url'],
                (float)$_POST['height'],
                (float)$_POST['weight'],
                (int)$_POST['candy'],
                (int)$_POST['required-candy-count'],
                (int)$_POST['egg-travel-length'],
                (float)$_POST['spawn-chance'],
                $_POST['spawn-time'],
                (float)$_POST['minimum-multiplier'],
                (float)$_POST['maximum-multiplier'],
                (int)$_POST['previous-evolution'],
                (int)$_POST['next-evolution'],
                $_POST['types'],
                $_POST['weaknesses']
            );

            $router->route("admin");
        } catch (BadFormDataException $e) {
            $message = $e->getMessage();
        } catch (InsufficientPermissionsException $e) {
            $router->route("user");
        }
    }
}

require_once '../src/Templates/admin/pokemon-form.phtml';