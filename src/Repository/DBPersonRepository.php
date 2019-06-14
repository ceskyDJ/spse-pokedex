<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Person;
use App\Exceptions\RepositoryDataManipulationException;
use App\Repository\Common\IPersonRepository;
use Nette\Database\ConstraintViolationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Database\UniqueConstraintViolationException;

/**
 * Person repository for database
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository
 */
class DBPersonRepository implements IPersonRepository
{

    /**
     * Database table name
     */
    public const PERSONS_TABLE = "persons";

    /**
     * @inject
     * @var \Nette\Database\Context db
     */
    private $db;

    /**
     * Gets person by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Person|null Person
     */
    public function getPersonById(int $id): ?Person
    {
        $personActiveRow = $this->db->table(self::PERSONS_TABLE)
            ->get($id);

        // No person found -> no sense to getting evolutions
        if ($personActiveRow === null) {
            return null;
        }

        return $this->createPersonFromDBData($personActiveRow);
    }

    /**
     * Creates person from database data
     *
     * @param \Nette\Database\Table\ActiveRow $personData Data from database (edited with Nette Database Explorer)
     *
     * @return \App\Entity\Person Person
     */
    public function createPersonFromDBData(ActiveRow $personData): Person
    {
        return new Person(
            $personData['person_id'],
            $personData['nick'],
            $personData['password'],
            $personData['email'],
            $personData['first_name'],
            $personData['last_name'],
            $personData['birth']
        );
    }

    /**
     * Gets all persons
     *
     * @return \App\Entity\Person[] Persons
     */
    public function getPersons(): array
    {
        $personActiveRows = $this->db->table(self::PERSONS_TABLE);

        return $this->createPersonsFromMultipleDBData($personActiveRows);
    }

    /**
     * Creates persons from multiple database data
     *
     * @param \Nette\Database\Table\Selection $personMultipleData Data from database (edited with Nette Database
     *     Explorer)
     *
     * @return \App\Entity\Person[] Persons
     */
    public function createPersonsFromMultipleDBData(Selection $personMultipleData): array
    {
        $persons = [];
        foreach ($personMultipleData as $personActiveRow) {
            $persons[] = $this->createPersonFromDBData($personActiveRow);
        }

        return $persons;
    }

    /**
     * Edits existing person
     *
     * @param \App\Entity\Person $editedPerson Person with edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name or email already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editPerson(Person $editedPerson): void
    {
        try {
            $this->db->table(self::PERSONS_TABLE)
                ->where("person_id", $editedPerson->getId())
                ->update(
                    [
                        'nick'       => $editedPerson->getNick(),
                        'password'   => $editedPerson->getPasswordHash(),
                        'email'      => $editedPerson->getEmail(),
                        'first_name' => $editedPerson->getFirstName(),
                        'last_name'  => $editedPerson->getLastName(),
                        'birth'      => $editedPerson->getBirth(),
                    ]
                );
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("Nickname and/or email are already exists.", 0, $e);
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Adds a new person
     *
     * @param string $nick Nickname
     * @param string $passwordHash Password hash
     * @param string $email Email
     * @param string $firstName First name
     * @param string $lastName Last name
     * @param string $birth Date of birth
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name or email already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function addPerson(
        string $nick,
        string $passwordHash,
        string $email,
        string $firstName,
        string $lastName,
        string $birth
    ): void {
        try {
            $this->db->table(self::PERSONS_TABLE)
                ->insert(
                    [
                        'nick'       => $nick,
                        'password'   => $passwordHash,
                        'email'      => $email,
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'birth'      => $birth,
                    ]
                );
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("Nickname and/or email are already exists.", 0, $e);
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }

    /**
     * Removes existing person
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removePerson(int $id): void
    {
        try {
            $this->db->table(self::PERSONS_TABLE)
                ->where("person_id", $id)
                ->delete();
        } catch (ConstraintViolationException $e) {
            throw new RepositoryDataManipulationException("There was some error while executing query", 0, $e);
        }
    }
}