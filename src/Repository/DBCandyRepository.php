<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Candy;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\Common\ICandyRepository;
use Nette\Database\ConstraintViolationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\UniqueConstraintViolationException;

/**
 * Candy repository for database
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository
 */
class DBCandyRepository implements ICandyRepository
{

    /**
     * Database table name
     */
    public const CANDIES_TABLE = "candies";

    /**
     * @inject
     * @var \Nette\Database\Context db
     */
    private $db;

    /**
     * Gets candy by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Candy|null Candy
     */
    public function getCandyById(int $id): ?Candy
    {
        $candyActiveRow = $this->db->table(self::CANDIES_TABLE)
            ->get($id);

        // No candy found -> no sense to getting evolutions
        if($candyActiveRow === null) {
            return null;
        }

        return $this->createCandyFromDBData($candyActiveRow);
    }

    /**
     * Creates candy from database data
     *
     * @param \Nette\Database\Table\ActiveRow $candyData Data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Candy Candy
     */
    public function createCandyFromDBData(ActiveRow $candyData): Candy
    {
        return new Candy($candyData['candy_id'], $candyData['name']);
    }

    /**
     * Gets candy by name
     *
     * @param string $name Name of the candy
     *
     * @return \App\Entity\Candy|null Candy
     */
    public function getCandyByName(string $name): ?Candy
    {
        $candyActiveRow = $this->db->table(self::CANDIES_TABLE)
            ->where("name", $name)
            ->fetch();

        // No candy found -> no sense to getting evolutions
        if($candyActiveRow === null) {
            return null;
        }

        return $this->createCandyFromDBData($candyActiveRow);
    }

    /**
     * Get all candies
     *
     * @return \App\Entity\Candy[] Candies
     */
    public function getCandies(): array
    {
        $candyActiveRows = $this->db->table(self::CANDIES_TABLE)
            ->fetchAll();

        return $this->createCandiesFromMultipleDBData($candyActiveRows);
    }

    /**
     * Get all candies with specified names
     *
     * @param string[] $names Names of wanted candies
     *
     * @return \App\Entity\Candy[] Candies
     */
    public function getCandiesFromNames(array $names): array
    {
        $candyActiveRows = $this->db->table(self::CANDIES_TABLE)
            ->where("name", $names)
            ->fetchAll();

        return $this->createCandiesFromMultipleDBData($candyActiveRows);
    }

    /**
     * Creates candies from multiple database data
     *
     * @param \Nette\Database\Table\ActiveRow[] $candyMultipleData Data from database (edited with Nette Database
     *     Explorer)
     *
     * @return \App\Entity\Candy[]
     */
    public function createCandiesFromMultipleDBData(array $candyMultipleData): ?array
    {
        $candies = [];
        foreach ($candyMultipleData as $candyActiveRow) {
            $candies[] = $this->createCandyFromDBData($candyActiveRow);
        }

        return $candies;
    }

    /**
     * Adds a new candy
     *
     * @param string $name Name
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addCandy(string $name): void
    {
        try {
            $this->db->table(self::CANDIES_TABLE)
                ->insert(['name' => $name]);
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("This name is already exists.", 0, $e);
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }
}