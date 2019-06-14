<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Type of pokemon
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Entity
 */
class Type
{
    /**
     * @var int|null id Identification number
     */
    private $id;
    /**
     * @var string name Name
     */
    private $name;

    /**
     * Type constructor
     *
     * @param int|null $id
     * @param string $name
     */
    public function __construct(?int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * Getter for name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fluent setter for name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName(string $name): Type
    {
        $this->name = $name;

        return $this;
    }
}