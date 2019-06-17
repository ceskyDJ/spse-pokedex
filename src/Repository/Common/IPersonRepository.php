<?php

declare(strict_types = 1);

namespace App\Repository\Common;

use App\Entity\Person;

/**
 * Interface for person repository
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Repository\Common
 */
interface IPersonRepository
{
    /**
     * Gets person by identification number
     *
     * @param int $id Identification number
     *
     * @return \App\Entity\Person|null
     */
    public function getPersonById(int $id): ?Person;

    /**
     * Gets person by nickname
     *
     * @param string $nick Nickname
     *
     * @return \App\Entity\Person|null Person
     */
    public function getPersonByNick(string $nick): ?Person;

    /**
     * Gets all persons
     *
     * @return \App\Entity\Person[] Persons
     */
    public function getPersons(): array;

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
    ): void;

    /**
     * Edits existing person
     *
     * @param \App\Entity\Person $editedPerson Person with edited data
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Name or email already exists
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function editPerson(Person $editedPerson): void;

    /**
     * Removes existing person
     *
     * @param int $id Identification number
     *
     * @throws \App\Exceptions\RepositoryDataManipulationException Other SQL error
     */
    public function removePerson(int $id): void;
}