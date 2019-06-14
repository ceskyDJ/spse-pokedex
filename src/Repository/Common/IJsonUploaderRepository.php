<?php

declare(strict_types = 1);

namespace App\Repository\Common;

/**
 * Interface for json uploader repository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository\Common
 */
interface IJsonUploaderRepository
{
    /**
     * Uploads pokemons to storage
     *
     * @param \App\Entity\Pokemon[] $pokemons Pokemons
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Official number, name and/or image already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadPokemons(array $pokemons): void;

    /**
     * Uploads types to storage
     *
     * @param \App\Entity\Type[] $types Types
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadTypes(array $types): void;

    /**
     * Uploads candies to storage
     *
     * @param \App\Entity\Candy[] $candies Candies
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function uploadCandies(array $candies): void;
}