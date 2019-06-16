<?php

declare(strict_types = 1);

namespace App\Tool;

use App\Entity\Candy;
use App\Entity\Pokemon;
use App\Entity\Type;
use DateTime;
use Exception;
use function array_keys;
use function array_merge;
use function array_shift;
use function in_array;
use function is_array;
use function json_decode;
use function str_replace;
use function strtolower;

/**
 * Class JsonParser
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Tool
 */
class JsonParser
{
    /**
     * @inject
     * @var \App\Repository\DBCandyRepository dbCandyRepository
     */
    private $dbCandyRepository;
    /**
     * @inject
     * @var \App\Repository\DBTypeRepository dbTypeRepository
     */
    private $dbTypeRepository;
    /**
     * @inject
     * @var \App\Repository\DBPokemonRepository dbPokemonRepository
     */
    private $dbPokemonRepository;
    /**
     * @inject
     * @var \App\Utils\FileHelper fileHelper
     */
    private $fileHelper;
    /**
     * @inject
     * @var \App\Utils\PhysicalUnitsHelper physicalUnitsHelper
     */
    private $physicalUnitsHelper;

    /**
     * Get all pokemons from pokemon json
     *
     * @param string $jsonAddress Pokemon json address
     *
     * @return \App\Entity\Pokemon[]
     * @throws \App\Exceptions\NonExistingFileException Non existing json file
     */
    public function parsePokemonJson(string $jsonAddress): array
    {
        $jsonPokemons = $this->parseJsonToArray($jsonAddress);

        $pokemons = [];
        foreach ($jsonPokemons as $jsonPokemon) {
            $eggTravelLength = $jsonPokemon['egg'] === "Not in Eggs"
                ? (int)$this->physicalUnitsHelper->convertToBaseMetricUnit(
                $jsonPokemon['egg']
            ) : null;

            $candyCount = isset($jsonPokemon['candy_count']) ? $jsonPokemon['candy_count'] : null;

            if (is_array($multipliers = $jsonPokemon['multipliers'])) {
                $minimumMultiplier = array_shift($multipliers);

                if (isset($multipliers[0])) {
                    $maximumMultiplier = array_shift($multipliers);
                } else {
                    $maximumMultiplier = null;
                }
            } else {
                $minimumMultiplier = null;
                $maximumMultiplier = null;
            }

            try {
                $spawnTime = new DateTime($jsonPokemon['spawn_time']);
            } catch (Exception $e) {
                $spawnTime = null;
            }

            $previousEvolution = isset($jsonPokemon['prev_evolution']) ? $this->dbPokemonRepository->getPokemonById(
                (int)$jsonPokemon['prev_evolution'][0]['num']
            ) : null;

            $nextEvolution = isset($jsonPokemon['next_evolution']) ? $this->dbPokemonRepository->getPokemonById(
                (int)$jsonPokemon['next_evolution'][0]['num']
            ) : null;

            $pokemons[] = new Pokemon(
                (int)$jsonPokemon['num'],
                $jsonPokemon['num'],
                $jsonPokemon['name'],
                $jsonPokemon['img'],
                $this->physicalUnitsHelper->convertToBaseMetricUnit($jsonPokemon['height']),
                $this->physicalUnitsHelper->convertToBaseWeightUnit($jsonPokemon['weight']),
                $this->dbCandyRepository->getCandyByName($jsonPokemon['candy']),
                $candyCount,
                $eggTravelLength,
                $jsonPokemon['spawn_chance'],
                $spawnTime,
                $minimumMultiplier,
                $maximumMultiplier,
                $previousEvolution,
                $nextEvolution,
                $this->dbTypeRepository->getTypesByNames($jsonPokemon['type']),
                $this->dbTypeRepository->getTypesByNames($jsonPokemon['weaknesses'])
            );
        }

        return $pokemons;
    }

    /**
     * Parses json string to associative array
     *
     * @param string $jsonAddress Pokemon json address
     *
     * @return array Associative array of json input
     * @throws \App\Exceptions\NonExistingFileException Non existing json file
     */
    private function parseJsonToArray(string $jsonAddress): array
    {
        $jsonData = $this->fileHelper->getFileContent($jsonAddress);
        $parsedJson = json_decode($jsonData, true);

        return array_shift($parsedJson);
    }

    /**
     * Get all (unique) types from pokemon json
     *
     * @param string $jsonAddress Pokemon json address
     *
     * @return \App\Entity\Type[] Types
     * @throws \App\Exceptions\NonExistingFileException Non existing json file
     */
    public function getTypesFromPokemonJson(string $jsonAddress): array
    {
        $jsonPokemons = $this->parseJsonToArray($jsonAddress);

        $types = [];
        foreach ($jsonPokemons as $jsonPokemon) {
            $jsonTypesPlusWeaknesses = array_merge($jsonPokemon['type'], $jsonPokemon['weaknesses']);

            foreach ($jsonTypesPlusWeaknesses as $jsonType) {
                $key = strtolower(str_replace(" ", "-", $jsonType));

                if (!in_array($key, array_keys($types))) {
                    $types[$key] = new Type(null, $jsonType);
                }
            }
        }

        return $types;
    }

    /**
     * Get all (unique) candies from pokemon json
     *
     * @param string $jsonAddress Pokemon json address
     *
     * @return \App\Entity\Candy[] Candies
     * @throws \App\Exceptions\NonExistingFileException Non existing json file
     */
    public function getCandiesFromPokemonJson(string $jsonAddress): array
    {
        $jsonPokemons = $this->parseJsonToArray($jsonAddress);

        $candies = [];
        foreach ($jsonPokemons as $jsonPokemon) {
            $key = strtolower(str_replace(" ", "-", $jsonPokemon['candy']));

            if (!in_array($key, array_keys($candies))) {
                $candies[$key] = new Candy(null, $jsonPokemon['candy']);
            }
        }

        return $candies;
    }
}