<?php

/**
 * Json upload
 * Upload content from json file in ../other/data directory
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\NonExistingFileException;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\DBJsonUploaderRepository;
use App\Tool\JsonParser;
use Nette\Database\UniqueConstraintViolationException;

// Configs
$jsonFile = '../other/data/pokedex.json';

// Prepare
echo "<h1>Json upload</h1>";
$error = false;

// Data manipulators
/**
 * @var \App\Tool\JsonParser $parser
 */
$parser = $container->getInstance(JsonParser::class);
/**
 * @var DBJsonUploaderRepository $dbJsonUploaderRepository
 */
$dbJsonUploaderRepository = $container->getInstance(DBJsonUploaderRepository::class);

// Types
echo "<h2>Types</h2>";

try {
    $types = $parser->getTypesFromPokemonJson($jsonFile);
} catch (NonExistingFileException $e) {
    $error = true;

    echo "<p>Json file not found.</p>";
}
try {
    $dbJsonUploaderRepository->uploadTypes($types);
} catch (RepositoryDataManipulationException $e) {
    $error = true;

    if ($e->getPrevious() instanceof UniqueConstraintViolationException) {
        echo "<p>Data isn't unique</p>\n";
    } else {
        echo "<p>There was an error while uploading data. Please control data state and structure.</p>\n";
    }
}

// Candies
echo "<h2>Candies</h2>";

try {
    $candies = $parser->getCandiesFromPokemonJson($jsonFile);
} catch (NonExistingFileException $e) {
    $error = true;

    echo "<p>Json file not found.</p>";
}
try {
    $dbJsonUploaderRepository->uploadCandies($candies);
} catch (RepositoryDataManipulationException $e) {
    $error = true;

    if ($e->getPrevious() instanceof UniqueConstraintViolationException) {
        echo "<p>Data isn't unique</p>\n";
    } else {
        echo "<p>There was an error while uploading data. Please control data state and structure.</p>\n";
    }
}

// Pokemons
echo "<h2>Pokemons</h2>";
// TODO: Complete this section
try {
    $pokemons = $parser->parsePokemonJson($jsonFile);
} catch (NonExistingFileException $e) {
    $error = true;

    echo "<p>Json file not found.</p>";
}
try {
    $dbJsonUploaderRepository->uploadPokemons($pokemons);
} catch (RepositoryDataManipulationException $e) {
    if ($e->getPrevious() instanceof UniqueConstraintViolationException) {
        echo "<p>Data isn't unique</p>\n";
    } else {
        echo "<p>There was an error while uploading data. Please control data state and structure.</p>\n";
    }
}

if($error === false) {
    echo "<p>Everything went OK. Data is been uploaded.</p>";
}