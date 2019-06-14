<?php

declare(strict_types = 1);

namespace App\Repository\Common;

use App\Entity\Type;

/**
 * Interface for type repository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository\Common
 */
interface ITypeRepository
{
    /**
     * Gets type by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Type|null Type
     */
    public function getTypeById(int $id): ?Type;

    /**
     * Gets type by name
     *
     * @param string $name Type's name
     *
     * @return \App\Entity\Type|null Type
     */
    public function getTypeByName(string $name): ?Type;

    /**
     * Gets all types
     *
     * @return \App\Entity\Type[] Types
     */
    public function getTypes(): array;

    /**
     * Gets all types with specified names
     *
     * @param string[] $names Names of wanted types
     *
     * @return \App\Entity\Type[] Types
     */
    public function getTypesByNames(array $names): array;

    /**
     * Adds a new type
     *
     * @param string $name Name
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addType(string $name): void;

    /**
     * Edits existing type
     *
     * @param \App\Entity\Type $editedType Type with edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editType(Type $editedType): void;

    /**
     * Removes existing type
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removeType(int $id): void;
}