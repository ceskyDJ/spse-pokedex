<?php

declare(strict_types = 1);

namespace App\Models;

use App\Entity\Person;
use App\Exceptions\BadFormDataException;
use App\Exceptions\InvalidUserPasswordException;
use App\Exceptions\RepositoryDataManipulationException;
use App\Model\Common\Model;
use DateTime;
use Exception;
use function filter_var;
use function mb_strlen;
use function session_regenerate_id;
use function str_replace;
use const FILTER_VALIDATE_EMAIL;

/**
 * User manager
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Models
 */
class PersonManager extends Model
{
    /**
     * @inject
     * @var \App\Repository\DBPersonRepository dbPersonRepository
     */
    private $dbPersonRepository;
    /**
     * @inject
     * @var \App\Utils\CryptographyHelper cryptographyHelper
     */
    private $cryptographyHelper;
    /**
     * @inject
     * @var \App\Routing\Router router
     */
    private $router;

    /**
     * Registers a new person
     *
     * @param string $nick Nickname
     * @param string $password Raw password
     * @param string $passwordAgain Raw password again (for verify validity)
     * @param string $email Email
     * @param string $firstName First name
     * @param string $lastName Last name
     * @param string $birth Date of birth
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     */
    public function register(
        string $nick,
        string $password,
        string $passwordAgain,
        string $email,
        string $firstName,
        string $lastName,
        string $birth
    ): void {
        // Checks
        if (empty($nick) || empty($password) || empty($email) || empty($firstName) || empty($lastName)
            || empty($birth)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if (mb_strlen($nick) < 3) {
            throw new BadFormDataException("Nick is too short.");
        }

        if ($password !== $passwordAgain) {
            throw new BadFormDataException("Passwords don't match.");
        }
        if (mb_strlen($password) < 8) {
            throw new BadFormDataException("Password is too short.");
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new BadFormDataException("Email isn't valid.");
        }

        if (mb_strlen($firstName) < 3 || mb_strlen($lastName) < 3) {
            throw new BadFormDataException("First and/or last name is too short.");
        }

        // Spaces aren't allowed
        $birth = str_replace(" ", "", $birth);
        try {
            new DateTime($birth);
        } catch (Exception $e) {
            throw new BadFormDataException("Birth format isn't valid.");
        }

        // Insert
        $passwordHash = $this->cryptographyHelper->hashPassword($password, $nick);
        try {
            $this->dbPersonRepository->addPerson($nick, $passwordHash, $email, $firstName, $lastName, $birth);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Some data isn't OK.");
        }
    }

    /**
     * Changes password of existing person
     *
     * @param int personId User's identification number
     * @param string $oldPassword Old raw password
     * @param string $newPassword New raw password
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InvalidUserPasswordException Invalid password
     */
    public function changePassword(int $personId, string $oldPassword, string $newPassword): void
    {
        // Checks
        $person = $this->dbPersonRepository->getPersonById($personId);

        // User must enter valid password for verifying
        $this->login($person->getNick(), $oldPassword);

        if (mb_strlen($newPassword) < 8) {
            throw new BadFormDataException("Password is too short.");
        }

        // Update
        $person->setPasswordHash($this->cryptographyHelper->hashPassword($newPassword, $person->getNick()));
        try {
            $this->dbPersonRepository->editPerson($person);
        } catch (RepositoryDataManipulationException $e) {
            // It's only changing password, this exception cannot been thrown
        }
    }

    /**
     * Logs-in existing person
     *
     * @param string $nick Nickname
     * @param string $password Raw password
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InvalidUserPasswordException Invalid person or password
     */
    public function login(string $nick, string $password): void
    {
        // Checks
        if (empty($nick) || empty($password)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if (mb_strlen($nick) < 3) {
            throw new InvalidUserPasswordException("Nick and/or password isn't valid.");
        }

        if (mb_strlen($password) < 8) {
            throw new InvalidUserPasswordException("Nick and/or password isn't valid.");
        }

        $person = $this->dbPersonRepository->getPersonByNick($nick);
        if (!$this->cryptographyHelper->verifyPassword($password, $nick, $person->getPasswordHash())) {
            throw new InvalidUserPasswordException("Nick and/or password isn't valid.");
        }

        // Login
        $this->systemLogin($person);
    }

    /**
     * @param \App\Entity\Person $person
     */
    private function systemLogin(Person $person)
    {
        session_regenerate_id();
        $_SESSION['person'] = $person;
    }

    /**
     * Logs-out logged-in person
     */
    public function logout(): void
    {
        $_SESSION['person'] = null;
        session_regenerate_id();

        $this->router->route("");
    }
}