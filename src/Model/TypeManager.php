<?php

declare(strict_types = 1);

namespace App\Model;

use App\Exceptions\BadFormDataException;
use App\Exceptions\InsufficientPermissionsException;
use App\Exceptions\RepositoryDataManipulationException;
use function mb_strlen;

/**
 * Type manager
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Model
 */
class TypeManager
{
    /**
     * @inject
     * @var \App\Repository\DBTypeRepository dbTypeRepository
     */
    private $dbTypeRepository;
    /**
     * @inject
     * @var \App\Models\PersonManager personManager
     */
    private $personManager;

    /**
     * Adds new pokemon type
     *
     * @param string $name Name
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function add(string $name): void
    {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        if(empty($name)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if(mb_strlen($name) < 3) {
            throw new BadFormDataException("Name is too short.");
        }

        // Insert
        try {
            $this->dbTypeRepository->addType($name);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Some data isn't OK. (type)");
        }
    }

    /**
     * Edits existing pokemon type
     *
     * @param int $typeId Identification number
     * @param string $newName New name
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function edit(int $typeId, string $newName): void
    {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        if(empty($newName)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if(mb_strlen($newName) < 3) {
            throw new BadFormDataException("Name is too short.");
        }

        $type = $this->dbTypeRepository->getTypeById($typeId);

        if($type === null) {
            throw new BadFormDataException("Specified type doesn't exists.");
        }

        // Update
        $type->setName($newName);
        try {
            $this->dbTypeRepository->editType($type);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Some data isn't OK. (type)");
        }
    }

    /**
     * Removes existing pokemon type
     *
     * @param int $typeId Identification number
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function remove(int $typeId): void
    {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        // Remove
        try {
            $this->dbTypeRepository->removeType($typeId);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Specified type doesn't exists.");
        }
    }
}