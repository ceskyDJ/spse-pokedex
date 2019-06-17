<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Entity represents candy for pokemons
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Entity
 */
class Candy
{
    /**
     * @var int|null id Intentifikační číslo
     */
    private $id;
    /**
     * @var string name Název pochutiny
     */
    private $name;

    /**
     * Candy constructor
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
     * @return Candy
     */
    public function setName(string $name): Candy
    {
        $this->name = $name;

        return $this;
    }
}