<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Pokemon;
use App\Entity\Type;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\Common\IPokemonRepository;
use Nette\Database\ConstraintViolationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\GroupedSelection;
use Nette\Database\UniqueConstraintViolationException;

/**
 * Pokemon repository for database
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository
 */
class DBPokemonRepository implements IPokemonRepository
{
    /**
     * Database table name
     */
    public const POKEMONS_TABLE = "pokemons";
    /**
     * Database table name of M:N connect table with types
     */
    public const POKEMON_TYPES_TABLE = "pokemon_types";
    /**
     * Database table name of M:N connect table with weaknesses (types)
     */
    public const POKEMON_WEAKNESSES_TABLE = "pokemon_weaknesses";

    /**
     * @inject
     * @var \Nette\Database\Context db
     */
    private $db;
    /**
     * @inject
     * @var \App\Repository\DBCandyRepository
     */
    private $dbCandyRepository;
    /**
     * @inject
     * @var \App\Repository\DBTypeRepository
     */
    private $dbTypeRepository;

    /**
     * Gets pokemon by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Pokemon|null Pokemon
     */
    public function getPokemonById(int $id): ?Pokemon
    {
        $pokemonActiveRow = $this->db->table(self::POKEMONS_TABLE)
            ->get($id);
        $pokemonTypeRelationActiveRow = $this->db->table(self::POKEMON_TYPES_TABLE)
            ->where("pokemon_id", $id)
            ->fetch();
        $pokemonWeaknessRelationActiveRow = $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
            ->where("pokemon_id", $id)
            ->fetch();

        // No pokemon found -> no sense to getting evolutions
        if ($pokemonActiveRow === null || $pokemonTypeRelationActiveRow === null
            || $pokemonWeaknessRelationActiveRow === null) {
            return null;
        }

        $pokemonTypeActiveRows = $pokemonTypeRelationActiveRow->related(DBTypeRepository::TYPES_TABLE.".type_id");
        $pokemonWeaknessActiveRows = $pokemonWeaknessRelationActiveRow->related(
            DBTypeRepository::TYPES_TABLE.".type_id"
        );

        $data = $this->constructPokemonData($pokemonActiveRow, $pokemonTypeActiveRows, $pokemonWeaknessActiveRows);

        return $this->createPokemonFromDBData($data);
    }

    /**
     * Constructs pokemon data from database results
     *
     * @param \Nette\Database\Table\ActiveRow $pokemonActiveRow Pokemon data
     * @param \Nette\Database\Table\GroupedSelection $pokemonTypeActiveRows Type data
     * @param \Nette\Database\Table\GroupedSelection $pokemonWeaknessActiveRows Weakness data
     *
     * @return \Nette\Database\Table\ActiveRow[] Complete pokemon data
     */
    private function constructPokemonData(
        ActiveRow $pokemonActiveRow,
        GroupedSelection $pokemonTypeActiveRows,
        GroupedSelection $pokemonWeaknessActiveRows
    ): array {
        $data['pokemon'] = $pokemonActiveRow;
        $data['candy'] = $pokemonActiveRow->ref(DBCandyRepository::CANDIES_TABLE, "candy_id");
        $data['previous-evolution'] = $pokemonActiveRow->ref(self::POKEMONS_TABLE, "previous_evolution");
        $data['next-evolution'] = $pokemonActiveRow->ref(self::POKEMONS_TABLE, "next_evolution");
        $data['types'] = $pokemonTypeActiveRows;
        $data['weaknesses'] = $pokemonWeaknessActiveRows;

        return $data;
    }

    /**
     * Creates pokemon from database data
     *
     * @param \Nette\Database\Table\ActiveRow[] $data Array of data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Pokemon Pokemon
     */
    public function createPokemonFromDBData(array $data): Pokemon
    {
        $pokemonData = $data['pokemon'];
        $candy = $this->dbCandyRepository->createCandyFromDBData($data['candy']);
        $previousEvolution = $this->createPokemonFromDBData($data['previous-evolution']);
        $nextEvolution = $this->createPokemonFromDBData($data['next-evolution']);
        $types = $this->dbTypeRepository->createTypesFromMultipleDBData($data['types']);
        $weaknesses = $this->dbTypeRepository->createTypesFromMultipleDBData($data['weaknesses']);

        return new Pokemon(
            $pokemonData['pokemon_id'],
            $pokemonData['official_number'],
            $pokemonData['name'],
            $pokemonData['image_url'],
            $pokemonData['height'],
            $pokemonData['weight'],
            $candy,
            $pokemonData['required_candy_count'],
            $pokemonData['egg_travel_length'],
            $pokemonData['spawn_chance'],
            $pokemonData['spawn_time'],
            $pokemonData['minimum_multiplier'],
            $pokemonData['maximum_multiplier'],
            $previousEvolution,
            $nextEvolution,
            $types,
            $weaknesses
        );
    }

    /**
     * Gets all pokemons
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemons(): array
    {
        $pokemonActiveRows = $this->db->table(self::POKEMONS_TABLE)
            ->fetchAll();
        $pokemonTypeActiveRows = $this->db->table(self::POKEMON_TYPES_TABLE)
            ->fetchAll();
        $pokemonWeaknessActiveRows = $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
            ->fetchAll();

        // TODO: Finish this work... Good luck :D

        foreach ($pokemonActiveRows as $pokemonActiveRow) {
        }
    }

    /**
     * Gets all pokemons owned by specific owner
     *
     * @param \App\Entity\Person $owner Owner
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemonsByOwner(Person $owner): array
    {
        // TODO: Implement getPokemonsByOwner() method.
    }

    /**
     * Gets all pokemons with specific type
     * One pokemon can have more types
     *
     * @param \App\Entity\Type $type Type of pokemon
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function getPokemonsByType(Type $type): array
    {
        // TODO: Implement getPokemonsByType() method.
    }

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
    ): void {
        try {
            $this->db->table(self::POKEMONS_TABLE)
                ->insert(
                    [
                        'official_number'      => $officialNumber,
                        'name'                 => $name,
                        'image_url'            => $imageUrl,
                        'height'               => $height,
                        'weight'               => $weight,
                        'candy_id'             => $candyId,
                        'required_candy_count' => $requiredCandyCount,
                        'egg_travel_length'    => $eggTravelLength,
                        'spawn_chance'         => $spawnChance,
                        'spawn_time'           => $spawnTime,
                        'minimum_multiplier'   => $minimumMultiplier,
                        'maximum_multiplier'   => $maximumMultiplier,
                        'previous_evolution'   => $previousEvolutionPokemonId,
                        'next_evolution'       => $nextEvolutionPokemonId,
                    ]
                );
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException(
                "Official number, name and/or image are already exists.", 0, $e
            );
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Edits existing pokemon
     *
     * @param \App\Entity\Pokemon $editedPokemon Pokemon edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Official number, name and/or image already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editPokemon(Pokemon $editedPokemon): void
    {
        try {
            $this->db->table(self::POKEMONS_TABLE)
                ->where("pokemon_id", $editedPokemon->getId())
                ->update(
                    [
                        'official_number'      => $editedPokemon->getOfficialNumber(),
                        'name'                 => $editedPokemon->getName(),
                        'image_url'            => $editedPokemon->getImageUrl(),
                        'height'               => $editedPokemon->getHeight(),
                        'weight'               => $editedPokemon->getWeight(),
                        'candy_id'             => $editedPokemon->getCandy()
                            ->getId(),
                        'required_candy_count' => $editedPokemon->getRequiredCandyCount(),
                        'egg_travel_length'    => $editedPokemon->getEggTravelLength(),
                        'spawn_chance'         => $editedPokemon->getSpawnChance(),
                        'spawn_time'           => $editedPokemon->getSpawnTime(),
                        'minimum_multiplier'   => $editedPokemon->getMinimumMultiplier(),
                        'maximum_multiplier'   => $editedPokemon->getMaximumMultiplier(),
                        'previous_evolution'   => $editedPokemon->getPreviousEvolution()
                            ->getId(),
                        'next_evolution'       => $editedPokemon->getNextEvolution()
                            ->getId(),
                    ]
                );
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException(
                "Official number, name and/or image are already exists.", 0, $e
            );
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Removes existing pokemon
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removePokemon(int $id): void
    {
        try {
            $this->db->table(self::POKEMONS_TABLE)
                ->where("pokemon_id", $id)
                ->delete();
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Create pokemons from multiple database data
     *
     * @param array $multipleData Array of data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Pokemon[] Pokemons
     */
    public function createPokemonsFromMultipleDBData(array $multipleData): array
    {
        $pokemons = [];
        foreach ($multipleData as $data) {
            $pokemons[] = $this->createPokemonFromDBData($data);
        }

        return $pokemons;
    }
}