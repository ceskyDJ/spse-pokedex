<?php

declare(strict_types = 1);

namespace App\Repository\Common;

use App\Entity\Candy;

/**
 * Interface for candy repository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository\Common
 */
interface ICandyRepository
{
    /**
     * Gets candy by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Candy|null Candy
     */
    public function getCandyById(int $id): ?Candy;

    /**
     * Gets candy by name
     *
     * @param string $name Name of the candy
     *
     * @return \App\Entity\Candy|null Candy
     */
    public function getCandyByName(string $name): ?Candy;

    /**
     * Get all candies
     *
     * @return \App\Entity\Candy[] Candies
     */
    public function getCandies(): array;

    /**
     * Get all candies with specified names
     *
     * @param string[] $names Names of wanted candies
     *
     * @return \App\Entity\Candy[] Candies
     */
    public function getCandiesFromNames(array $names): array;

    /**
     * Adds a new candy
     *
     * @param string $name Name
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addCandy(string $name): void;
}