<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Person;
use App\Entity\Pokemon;
use App\Entity\Type;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\Common\IPokemonRepository;
use DateTime;
use Exception;
use Nette\Database\ConstraintViolationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\UniqueConstraintViolationException;
use function str_pad;
use const STR_PAD_LEFT;

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

        // No pokemon found -> no sense to getting evolutions
        if ($pokemonActiveRow === null) {
            return null;
        }

        return $this->createPokemonFromDBData($data = $this->constructPokemonData($pokemonActiveRow));
    }

    /**
     * Gets pokemon by name
     *
     * @param string $name Name
     *
     * @return \App\Entity\Pokemon|null Pokemon
     */
    public function getPokemonByName(string $name): ?Pokemon
    {
        $pokemonActiveRow = $this->db->table(self::POKEMONS_TABLE)
            ->where("name", $name)
            ->fetch();

        // No pokemon found -> no sense to getting evolutions
        if ($pokemonActiveRow === null) {
            return null;
        }

        return $this->createPokemonFromDBData($this->constructPokemonData($pokemonActiveRow));
    }

    /**
     * Constructs pokemon data from database results
     *
     * @param \Nette\Database\Table\ActiveRow|null $pokemonActiveRow Pokemon data
     *
     * @return \Nette\Database\Table\ActiveRow[]|null Complete pokemon data
     */
    private function constructPokemonData(?ActiveRow $pokemonActiveRow): ?array
    {
        // No pokemon data -> no sense to continue
        if ($pokemonActiveRow === null) {
            return null;
        }

        /**
         * @var \Nette\Database\Table\ActiveRow[] $pokemonTypeActiveRows
         */
        $pokemonTypeActiveRows = $this->db->table(self::POKEMON_TYPES_TABLE)
            ->where("pokemon_id", $pokemonActiveRow['pokemon_id'])
            ->fetchAll();
        /**
         * @var \Nette\Database\Table\ActiveRow[] $pokemonWeaknessActiveRows
         */
        $pokemonWeaknessActiveRows = $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
            ->where("pokemon_id", $pokemonActiveRow['pokemon_id'])
            ->fetchAll();

        $pokemonTypes = [];
        foreach ($pokemonTypeActiveRows as $pokemonTypeActiveRow) {
            $pokemonTypes[] = $pokemonTypeActiveRow->ref("types", "type_id");
        }

        $pokemonWeaknesses = [];
        foreach ($pokemonWeaknessActiveRows as $pokemonWeaknessActiveRow) {
            $pokemonWeaknesses[] = $pokemonWeaknessActiveRow->ref("types", "type_id");
        }

        $data['pokemon'] = $pokemonActiveRow;
        $data['candy'] = $pokemonActiveRow->ref(DBCandyRepository::CANDIES_TABLE, "candy_id");
        $data['previous-evolution'] = $pokemonActiveRow->ref(self::POKEMONS_TABLE, "previous_evolution");
        $data['next-evolution'] = $pokemonActiveRow->ref(self::POKEMONS_TABLE, "next_evolution");
        $data['types'] = $pokemonTypes;
        $data['weaknesses'] = $pokemonWeaknesses;

        return $data;
    }

    /**
     * Creates pokemon from database data
     *
     * @param \Nette\Database\Table\ActiveRow[]|null $data Array of data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Pokemon|null Pokemon
     */
    public function createPokemonFromDBData(?array $data): ?Pokemon
    {
        // No data -> no sense to continue
        if ($data === null) {
            return null;
        }

        $pokemonData = $data['pokemon'];

        $officialNumber = str_pad((string)$pokemonData['official_number'], 3, "0", STR_PAD_LEFT);
        $candy = $this->dbCandyRepository->createCandyFromDBData($data['candy']);
        try {
            $spawnTime = new DateTime($pokemonData['spawn_time']->format("%H:%I"));
        } catch (Exception $e) {
            $spawnTime = null;
        }
        $previousEvolution = $this->createPokemonFromDBData($this->constructPokemonData($data['previous-evolution']));
        $nextEvolution = $this->createPokemonFromDBData($this->constructPokemonData($data['next-evolution']));
        $types = $this->dbTypeRepository->createTypesFromMultipleDBData($data['types']);
        $weaknesses = $this->dbTypeRepository->createTypesFromMultipleDBData($data['weaknesses']);

        return new Pokemon(
            $pokemonData['pokemon_id'], $officialNumber,
            $pokemonData['name'],
            $pokemonData['image_url'],
            $pokemonData['height'],
            $pokemonData['weight'],
            $candy,
            $pokemonData['required_candy_count'],
            $pokemonData['egg_travel_length'], $pokemonData['spawn_chance'], $spawnTime,
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
     * @param string|null $spawnTime Time of most active spawning
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
        ?string $spawnTime,
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

            $pokemonId = $this->db->table(self::POKEMONS_TABLE)
                ->where("name", $name)
                ->fetch()['pokemon_id'];

            $pokemonWithTypes = [];
            foreach ($types as $type) {
                // Type can be int (identification number) or object
                if ($type instanceof Type) {
                    $type = $type->getId();
                }

                $pokemonWithTypes[] = [
                    'pokemon_id' => $pokemonId,
                    'type_id'    => $type,
                ];
            }

            $pokemonWithWeaknesses = [];
            foreach ($weaknesses as $weakness) {
                // Weakness can be int (identification number) or object
                if ($weakness instanceof Type) {
                    $weakness = $weakness->getId();
                }

                $pokemonWithWeaknesses[] = [
                    'pokemon_id' => $pokemonId,
                    'type_id'    => $weakness,
                ];
            }

            $this->db->table(self::POKEMON_TYPES_TABLE)
                ->insert($pokemonWithTypes);
            $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
                ->insert($pokemonWithWeaknesses);
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

            // Remove relations first
            $this->db->table(self::POKEMON_TYPES_TABLE)
                ->where("pokemon_id", $editedPokemon->getId())
                ->delete();
            $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
                ->where("pokemon_id", $editedPokemon->getId())
                ->delete();

            // Add valid relations
            $pokemonId = $editedPokemon->getId();
            $types = $editedPokemon->getTypes();
            $pokemonWithTypes = [];
            foreach ($types as $type) {
                // Type can be int (identification number) or object
                if ($type instanceof Type) {
                    $type = $type->getId();
                }

                $pokemonWithTypes[] = [
                    'pokemon_id' => $pokemonId,
                    'type_id'    => $type,
                ];
            }

            $weaknesses = $editedPokemon->getWeaknesses();
            $pokemonWithWeaknesses = [];
            foreach ($weaknesses as $weakness) {
                // Weakness can be int (identification number) or object
                if ($weakness instanceof Type) {
                    $weakness = $weakness->getId();
                }

                $pokemonWithWeaknesses[] = [
                    'pokemon_id' => $pokemonId,
                    'type_id'    => $weakness,
                ];
            }

            $this->db->table(self::POKEMON_TYPES_TABLE)
                ->insert($pokemonWithTypes);
            $this->db->table(self::POKEMON_WEAKNESSES_TABLE)
                ->insert($pokemonWithWeaknesses);
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