<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Type;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\Common\ITypeRepository;
use Nette\Database\ConstraintViolationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Database\UniqueConstraintViolationException;

/**
 * Type repository for database
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository
 */
class DBTypeRepository implements ITypeRepository
{
    /**
     * Database table name
     */
    public const TYPES_TABLE = "types";

    /**
     * @inject
     * @var \Nette\Database\Context db
     */
    private $db;

    /**
     * Gets type by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Type|null Type
     */
    public function getTypeById(int $id): ?Type
    {
        $typeActiveRow = $this->db->table(self::TYPES_TABLE)
            ->get($id);

        // No type found -> no sense to getting evolutions
        if($typeActiveRow === null) {
            return null;
        }

        return $this->createTypeFromDBData($typeActiveRow);
    }

    /**
     * Creates type from database data
     *
     * @param \Nette\Database\Table\ActiveRow $typeData Data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Type Type
     */
    public function createTypeFromDBData(ActiveRow $typeData): Type
    {
        return new Type($typeData['type_id'], $typeData['name']);
    }

    /**
     * Gets type by name
     *
     * @param string $name Type's name
     *
     * @return \App\Entity\Type|null Type
     */
    public function getTypeByName(string $name): ?Type
    {
        $typeActiveRow = $this->db->table(self::TYPES_TABLE)
            ->where("name", $name)
            ->fetch();

        // No type found -> no sense to getting evolutions
        if ($typeActiveRow === null) {
            return null;
        }

        return $this->createTypeFromDBData($typeActiveRow);
    }

    /**
     * Gets all types
     *
     * @return Type[] Types
     */
    public function getTypes(): array
    {
        $typeActiveRows = $this->db->table(self::TYPES_TABLE);

        return $this->createTypesFromMultipleDBData($typeActiveRows);
    }

    /**
     * Creates types from multiple database data
     *
     * @param \Nette\Database\Table\Selection $typeMultipleData Data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Type[] Types
     */
    public function createTypesFromMultipleDBData(Selection $typeMultipleData): array
    {
        $types = [];
        foreach ($typeMultipleData as $typeActiveRow) {
            $types[] = $this->createTypeFromDBData($typeActiveRow);
        }

        return $types;
    }

    /**
     * Gets all types with specified names
     *
     * @param string[] $names Names of wanted types
     *
     * @return \App\Entity\Type[] Types
     */
    public function getTypesByNames(array $names): array
    {
        $typeActiveRows = $this->db->table(self::TYPES_TABLE)
            ->where("name", $names);

        return $this->createTypesFromMultipleDBData($typeActiveRows);
    }

    /**
     * Adds a new type
     *
     * @param string $name Name
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addType(string $name): void
    {
        try {
            $this->db->table(self::TYPES_TABLE)
                ->insert(['name' => $name]);
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("This name is already exists.", 0, $e);
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Edits existing type
     *
     * @param \App\Entity\Type $editedType Type with edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editType(Type $editedType): void
    {
        try {
            $this->db->table(self::TYPES_TABLE)
                ->where("type_id", $editedType->getId())
                ->update(['name' => $editedType->getName()]);
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("This name is already exists.", 0, $e);
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Removes existing type
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removeType(int $id): void
    {
        try {
            $this->db->table(self::TYPES_TABLE)
                ->where("type_id", $id)
                ->delete();
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }
}