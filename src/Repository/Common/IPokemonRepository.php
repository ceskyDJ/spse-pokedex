<?php

declare(strict_types = 1);

namespace App\Repository\Common;

use App\Entity\Person;
use App\Entity\Pokemon;
use App\Entity\Type;

/**
 * Interface for pokemon repository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository\Common
 */
interface IPokemonRepository
{
    /**
     * Gets pokemon by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Pokemon|null Pokemon
     */
    public function getPokemonById(int $id): ?Pokemon;

    /**
     * Gets pokemon by name
     *
     * @param string $name Name
     *
     * @return \App\Entity\Pokemon|null Pokemon
     */
    public function getPokemonByName(string $name): ?Pokemon;

    /**
     * Gets all pokemons
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemons(): array;

    /**
     * Gets all pokemons owned by specific owner
     *
     * @param \App\Entity\Person $owner Owner
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemonsByOwner(Person $owner): array;

    /**
     * Gets all pokemons with specific type
     * One pokemon can have more types
     *
     * @param \App\Entity\Type $type Type of pokemon
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemonsByType(Type $type): array;

    /**
     * Adds a new pokemon
     *
     * @param string $officialNumber Number in official pokedex
     * @param string $name Name
     * @param string $imageUrl URL to profile image file
     * @param float $height Height (in meters)
     * @param float $weight Weight (in kilograms)
     * @param int|null $candyId Candy for evolution - identification number
     * @param int|null $requiredCandyCount Number of candies required for evolution
     * @param int|null $eggTravelLength Length of way to travel with egg to birth
     * @param float $spawnChance Chance to spawn (percent in real number form)
     * @param string $spawnTime Time of most active spawning
     * @param float|null $minimumMultiplier Minimum multiplier of combat power
     * @param float|null $maximumMultiplier Maximum multiplier of combat power
     * @param int|null $previousEvolutionPokemonId Previous evolution (pokemon) identification number
     * @param int|null $nextEvolutionPokemonId Next evolution (pokemon) identification number
     * @param array $types Types (advantages)
     * @param array $weaknesses Weaknesses
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Official number, name and/or image already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addPokemon(
        string $officialNumber,
        string $name,
        string $imageUrl,
        float $height,
        float $weight,
        ?int $candyId,
        ?int $requiredCandyCount,
        ?int $eggTravelLength,
        float $spawnChance,
        string $spawnTime,
        ?float $minimumMultiplier,
        ?float $maximumMultiplier,
        ?int $previousEvolutionPokemonId,
        ?int $nextEvolutionPokemonId,
        array $types,
        array $weaknesses
    ): void;

    /**
     * Edits existing pokemon
     *
     * @param \App\Entity\Pokemon $editedPokemon Pokemon edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Official number, name and/or image already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editPokemon(Pokemon $editedPokemon): void;

    /**
     * Removes existing pokemon
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removePokemon(int $id): void;
}