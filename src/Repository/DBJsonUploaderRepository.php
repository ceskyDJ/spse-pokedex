<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Repository\Common\IJsonUploaderRepository;

/**
 * Class DBJsonUploaderRepository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository
 */
class DBJsonUploaderRepository implements IJsonUploaderRepository
{
    /**
     * @inject
     * @var \App\Repository\DBPokemonRepository dbPokemonRepository
     */
    private $dbPokemonRepository;
    /**
     * @inject
     * @var \App\Repository\DBTypeRepository dbTypeRepository
     */
    private $dbTypeRepository;
    /**
     * @inject
     * @var \App\Repository\DBCandyRepository dbCandyRepository
     */
    private $dbCandyRepository;

    /**
     * Uploads pokemons to storage
     *
     * @param \App\Entity\Pokemon[] $pokemons Pokemons
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Official number, name and/or image already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadPokemons(array $pokemons): void
    {
        foreach ($pokemons as $pokemon) {
            $spawnTime = $pokemon->getSpawnTime() !== null ? $pokemon->getSpawnTime()
                ->format("Y-m-d H:i:s") : "";

            $previousEvolution = $pokemon->getPreviousEvolution() !== null ? $pokemon->getPreviousEvolution()
                ->getId() : null;
            $nextEvolution = $pokemon->getNextEvolution() !== null ? $pokemon->getNextEvolution()
                ->getId() : null;

            $this->dbPokemonRepository->addPokemon(
                $pokemon->getOfficialNumber(),
                $pokemon->getName(),
                $pokemon->getImageUrl(),
                $pokemon->getHeight(),
                $pokemon->getWeight(),
                $pokemon->getCandy()
                    ->getId(),
                $pokemon->getRequiredCandyCount(),
                $pokemon->getEggTravelLength(),
                $pokemon->getSpawnChance(),
                $spawnTime,
                $pokemon->getMinimumMultiplier(),
                $pokemon->getMaximumMultiplier(),
                $previousEvolution,
                $nextEvolution,
                $pokemon->getTypes(),
                $pokemon->getWeaknesses()
            );
        }
    }

    /**
     * Uploads types to storage
     *
     * @param \App\Entity\Type[] $types Types
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadTypes(array $types): void
    {
        foreach ($types as $type) {
            $this->dbTypeRepository->addType($type->getName());
        }
    }

    /**
     * Uploads candies to storage
     *
     * @param \App\Entity\Candy[] $candies Candies
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadCandies(array $candies): void
    {
        foreach ($candies as $candy) {
            $this->dbCandyRepository->addCandy($candy->getName());
        }
    }
}