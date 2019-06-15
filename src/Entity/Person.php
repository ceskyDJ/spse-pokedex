<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Entity represents human (owner of pokemon or administrator etc.)
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Entity
 */
class Person
{
    /**
     * @var int|null id Indetification number
     */
    private $id;
    /**
     * @var string nick Nickname
     */
    private $nick;
    /**
     * @var string passwordHash Password hash
     */
    private $passwordHash;
    /**
     * @var string email Email address
     */
    private $email;
    /**
     * @var bool admin Is person admin?
     */
    private $admin;
    /**
     * @var string firstName First name
     */
    private $firstName;
    /**
     * @var string lastName Last name
     */
    private $lastName;
    /**
     * @var \DateTime birth Date of birth
     */
    private $birth;

    /**
     * Person constructor
     *
     * @param int|null $id
     * @param string $nick
     * @param string $passwordHash
     * @param string $email
     * @param bool $admin
     * @param string $firstName
     * @param string $lastName
     * @param \DateTime $birth
     */
    public function __construct(
        ?int $id,
        string $nick,
        string $passwordHash,
        string $email,
        bool $admin,
        string $firstName,
        string $lastName,
        DateTime $birth
    ) {
        $this->id = $id;
        $this->nick = $nick;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->admin = $admin;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birth = $birth;
    }

    /**
     * Getter for id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for nick
     *
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * Fluent setter for nick
     *
     * @param string $nick
     *
     * @return Person
     */
    public function setNick(string $nick): Person
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Getter for passwordHash
     *
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * Fluent setter for passwordHash
     *
     * @param string $passwordHash
     *
     * @return Person
     */
    public function setPasswordHash(string $passwordHash): Person
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    /**
     * Getter for email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Fluent setter for email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail(string $email): Person
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * Fluent setter for admin
     *
     * @param bool $admin
     *
     * @return Person
     */
    public function setAdmin(bool $admin): Person
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Getter for firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Fluent setter for firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName(string $firstName): Person
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Getter for lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Fluent setter for lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName(string $lastName): Person
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Getter for birth
     *
     * @return \DateTime
     */
    public function getBirth(): DateTime
    {
        return $this->birth;
    }

    /**
     * Fluent setter for birth
     *
     * @param \DateTime $birth
     *
     * @return Person
     */
    public function setBirth(DateTime $birth): Person
    {
        $this->birth = $birth;

        return $this;
    }
}