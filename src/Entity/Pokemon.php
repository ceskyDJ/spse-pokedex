<?php

declare(strict_types = 1);

namespace App\Entity;

use DateTime;

/**
 * Pokemon
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Entity
 */
class Pokemon
{
    /**
     * @var int|null id Indetification number
     */
    private $id;
    /**
     * @var string officialNumber Number in official pokedex
     */
    private $officialNumber;
    /**
     * @var string name Name
     */
    private $name;
    /**
     * @var string imageUrl URL to profile image file
     */
    private $imageUrl;
    /**
     * @var float height Height (in meters)
     */
    private $height;
    /**
     * @var float weight Weight (in kilograms)
     */
    private $weight;
    /**
     * @var \App\Entity\Candy|null candy Candy for evolution
     */
    private $candy;
    /**
     * @var int|null requiredCandyCount Number of candies required for evolution
     */
    private $requiredCandyCount;
    /**
     * @var int|null eggTravelLength Length of way to travel with egg to birth
     */
    private $eggTravelLength;
    /**
     * @var float spawnChance Chance to spawn (percent in real number form)
     */
    private $spawnChance;
    /**
     * @var \DateTime|null spawnTime Time of most active spawning
     */
    private $spawnTime;
    /**
     * @var float|null minimumMultiplier Minimum multiplier of combat power
     */
    private $minimumMultiplier;
    /**
     * @var float|null maximumMultiplier Maximum multiplier of combat power
     */
    private $maximumMultiplier;
    /**
     * @var \App\Entity\Pokemon|null previousEvolution Previous evolution (pokemon)
     */
    private $previousEvolution;
    /**
     * @var \App\Entity\Pokemon|null nextEvolution Next evolution (pokemon)
     */
    private $nextEvolution;
    /**
     * @var \App\Entity\Type[] Types (advantages)
     */
    private $types;
    /**
     * @var \App\Entity\Type[] weaknesses Weaknesses
     */
    private $weaknesses;

    /**
     * Pokemon constructor
     *
     * @param int|null $id
     * @param string $officialNumber
     * @param string $name
     * @param string $imageUrl
     * @param float $height
     * @param float $weight
     * @param \App\Entity\Candy|null $candy
     * @param int|null $requiredCandyCount
     * @param int|null $eggTravelLength
     * @param float $spawnChance
     * @param \DateTime|null $spawnTime
     * @param float|null $minimumMultiplier
     * @param float|null $maximumMultiplier
     * @param \App\Entity\Pokemon|null $previousEvolution
     * @param \App\Entity\Pokemon|null $nextEvolution
     * @param \App\Entity\Type[] $types
     * @param \App\Entity\Type[] $weaknesses
     */
    public function __construct(
        ?int $id,
        string $officialNumber,
        string $name,
        string $imageUrl,
        float $height,
        float $weight,
        ?Candy $candy,
        ?int $requiredCandyCount,
        ?int $eggTravelLength,
        float $spawnChance,
        ?DateTime $spawnTime,
        ?float $minimumMultiplier,
        ?float $maximumMultiplier,
        ?Pokemon $previousEvolution,
        ?Pokemon $nextEvolution,
        array $types,
        array $weaknesses
    ) {
        $this->id = $id;
        $this->officialNumber = $officialNumber;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
        $this->height = $height;
        $this->weight = $weight;
        $this->candy = $candy;
        $this->requiredCandyCount = $requiredCandyCount;
        $this->eggTravelLength = $eggTravelLength;
        $this->spawnChance = $spawnChance;
        $this->spawnTime = $spawnTime;
        $this->minimumMultiplier = $minimumMultiplier;
        $this->maximumMultiplier = $maximumMultiplier;
        $this->previousEvolution = $previousEvolution;
        $this->nextEvolution = $nextEvolution;
        $this->types = $types;
        $this->weaknesses = $weaknesses;
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
     * Getter for officialNumber
     *
     * @return string
     */
    public function getOfficialNumber(): string
    {
        return $this->officialNumber;
    }

    /**
     * Fluent setter for officialNumber
     *
     * @param string $officialNumber
     *
     * @return Pokemon
     */
    public function setOfficialNumber(string $officialNumber): Pokemon
    {
        $this->officialNumber = $officialNumber;

        return $this;
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
     * @return Pokemon
     */
    public function setName(string $name): Pokemon
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for imageUrl
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * Fluent setter for imageUrl
     *
     * @param string $imageUrl
     *
     * @return Pokemon
     */
    public function setImageUrl(string $imageUrl): Pokemon
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Getter for height
     *
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * Fluent setter for height
     *
     * @param float $height
     *
     * @return Pokemon
     */
    public function setHeight(float $height): Pokemon
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Getter for weight
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Fluent setter for weight
     *
     * @param float $weight
     *
     * @return Pokemon
     */
    public function setWeight(float $weight): Pokemon
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Getter for candy
     *
     * @return \App\Entity\Candy|null
     */
    public function getCandy(): ?Candy
    {
        return $this->candy;
    }

    /**
     * Fluent setter for candy
     *
     * @param \App\Entity\Candy|null $candy
     *
     * @return Pokemon
     */
    public function setCandy(?Candy $candy): Pokemon
    {
        $this->candy = $candy;

        return $this;
    }

    /**
     * Getter for requiredCandyCount
     *
     * @return int|null
     */
    public function getRequiredCandyCount(): ?int
    {
        return $this->requiredCandyCount;
    }

    /**
     * Fluent setter for requiredCandyCount
     *
     * @param int|null $requiredCandyCount
     *
     * @return Pokemon
     */
    public function setRequiredCandyCount(?int $requiredCandyCount): Pokemon
    {
        $this->requiredCandyCount = $requiredCandyCount;

        return $this;
    }

    /**
     * Getter for eggTravelLength
     *
     * @return int|null
     */
    public function getEggTravelLength(): ?int
    {
        return $this->eggTravelLength;
    }

    /**
     * Fluent setter for eggTravelLength
     *
     * @param int|null $eggTravelLength
     *
     * @return Pokemon
     */
    public function setEggTravelLength(?int $eggTravelLength): Pokemon
    {
        $this->eggTravelLength = $eggTravelLength;

        return $this;
    }

    /**
     * Getter for spawnChance
     *
     * @return float
     */
    public function getSpawnChance(): float
    {
        return $this->spawnChance;
    }

    /**
     * Fluent setter for spawnChance
     *
     * @param float $spawnChance
     *
     * @return Pokemon
     */
    public function setSpawnChance(float $spawnChance): Pokemon
    {
        $this->spawnChance = $spawnChance;

        return $this;
    }

    /**
     * Getter for spawnTime
     *
     * @return \DateTime|null
     */
    public function getSpawnTime(): ?DateTime
    {
        return $this->spawnTime;
    }

    /**
     * Fluent setter for spawnTime
     *
     * @param \DateTime|null $spawnTime
     *
     * @return Pokemon
     */
    public function setSpawnTime(?DateTime $spawnTime): Pokemon
    {
        $this->spawnTime = $spawnTime;

        return $this;
    }

    /**
     * Getter for minimumMultiplier
     *
     * @return float|null
     */
    public function getMinimumMultiplier(): ?float
    {
        return $this->minimumMultiplier;
    }

    /**
     * Fluent setter for minimumMultiplier
     *
     * @param float|null $minimumMultiplier
     *
     * @return Pokemon
     */
    public function setMinimumMultiplier(?float $minimumMultiplier): Pokemon
    {
        $this->minimumMultiplier = $minimumMultiplier;

        return $this;
    }

    /**
     * Getter for maximumMultiplier
     *
     * @return float|null
     */
    public function getMaximumMultiplier(): ?float
    {
        return $this->maximumMultiplier;
    }

    /**
     * Fluent setter for maximumMultiplier
     *
     * @param float|null $maximumMultiplier
     *
     * @return Pokemon
     */
    public function setMaximumMultiplier(?float $maximumMultiplier): Pokemon
    {
        $this->maximumMultiplier = $maximumMultiplier;

        return $this;
    }

    /**
     * Getter for previousEvolution
     *
     * @return \App\Entity\Pokemon|null
     */
    public function getPreviousEvolution(): ?Pokemon
    {
        return $this->previousEvolution;
    }

    /**
     * Fluent setter for previousEvolution
     *
     * @param \App\Entity\Pokemon|null $previousEvolution
     *
     * @return Pokemon
     */
    public function setPreviousEvolution(?Pokemon $previousEvolution): Pokemon
    {
        $this->previousEvolution = $previousEvolution;

        return $this;
    }

    /**
     * Getter for nextEvolution
     *
     * @return \App\Entity\Pokemon|null
     */
    public function getNextEvolution(): ?Pokemon
    {
        return $this->nextEvolution;
    }

    /**
     * Fluent setter for nextEvolution
     *
     * @param \App\Entity\Pokemon|null $nextEvolution
     *
     * @return Pokemon
     */
    public function setNextEvolution(?Pokemon $nextEvolution): Pokemon
    {
        $this->nextEvolution = $nextEvolution;

        return $this;
    }

    /**
     * Getter for types
     *
     * @return \App\Entity\Type[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Fluent setter for types
     *
     * @param \App\Entity\Type[] $types
     *
     * @return Pokemon
     */
    public function setTypes(array $types): Pokemon
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Getter for weaknesses
     *
     * @return \App\Entity\Type[]
     */
    public function getWeaknesses(): array
    {
        return $this->weaknesses;
    }

    /**
     * Fluent setter for weaknesses
     *
     * @param \App\Entity\Type[] $weaknesses
     *
     * @return Pokemon
     */
    public function setWeaknesses(array $weaknesses): Pokemon
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }
}