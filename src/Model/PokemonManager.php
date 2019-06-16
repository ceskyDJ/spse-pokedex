<?php

declare(strict_types = 1);

namespace App\Model;

use App\Exceptions\BadFormDataException;
use App\Exceptions\InsufficientPermissionsException;
use App\Exceptions\RepositoryDataManipulationException;
use DateTime;
use Exception;
use function filter_var;
use function getimagesize;
use function mb_strlen;
use function preg_match;
use const FILTER_VALIDATE_URL;

/**
 * Pokemon manager
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Model
 */
class PokemonManager
{
    /**
     * @inject
     * @var \App\Repository\DBPokemonRepository dbPokemonRepository
     */
    private $dbPokemonRepository;
    /**
     * @inject
     * @var \App\Models\PersonManager personManager
     */
    private $personManager;
    /**
     * @inject
     * @var \App\Utils\PhysicalUnitsHelper physicalUnitsHelper
     */
    private $physicalUnitsHelper;
    /**
     * @inject
     * @var \App\Repository\DBCandyRepository dbCandyRepository
     */
    private $dbCandyRepository;
    /**
     * @inject
     * @var \App\Repository\DBTypeRepository dbTypeRepository
     */
    private $dbTypeRepository;

    /**
     * Adds new pokemon
     *
     * @param string $officialNumber Number in official pokedex
     * @param string $name Name
     * @param string $imageUrl URL to profile image file
     * @param float $height Height (in meters)
     * @param float $weight Weight (in kilograms)
     * @param int|null $candyId Candy for evolution - identification number
     * @param int|null $requiredCandyCount Number of candies required for evolution
     * @param int|null $eggTravelLength Length of way to travel with egg to birth
     * @param float $spawnChance Chance to spawn (percent in real number form)
     * @param string|null $spawnTime Time of most active spawning
     * @param float|null $minimumMultiplier Minimum multiplier of combat power
     * @param float|null $maximumMultiplier Maximum multiplier of combat power
     * @param int|null $previousEvolutionPokemonId Previous evolution (pokemon) identification number
     * @param int|null $nextEvolutionPokemonId Next evolution (pokemon) identification number
     * @param array $types Types (advantages)
     * @param array $weaknesses Weaknesses
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function add(
        string $officialNumber,
        string $name,
        string $imageUrl,
        float $height,
        float $weight,
        ?int $candyId,
        ?int $requiredCandyCount,
        int $eggTravelLength,
        float $spawnChance,
        ?string $spawnTime,
        ?float $minimumMultiplier,
        ?float $maximumMultiplier,
        ?int $previousEvolutionPokemonId,
        ?int $nextEvolutionPokemonId,
        array $types,
        array $weaknesses
    ): void {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        if (empty($officialNumber) || empty($name) || empty($imageUrl) || empty($height) || empty($weight)
            || empty($types)
            || empty($weaknesses)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if (!preg_match("%\d{3}%", $officialNumber)) {
            throw new BadFormDataException("Official number format isn't valid.");
        }

        if (mb_strlen($name) < 3) {
            throw new BadFormDataException("Pokemon's name is too short.");
        }

        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            throw new BadFormDataException("Image URL has bad format.");
        }
        if (getimagesize($imageUrl)[0] === 0 || getimagesize($imageUrl)[1] === 0) {
            throw new BadFormDataException("Image URL doesn't point to valid image.");
        }

        try {
            new DateTime($spawnTime);
        } catch (Exception $e) {
            throw new  BadFormDataException("Spawn time format isn't valid.");
        }

        // Insert
        try {
            $this->dbPokemonRepository->addPokemon(
                $officialNumber,
                $name,
                $imageUrl,
                $height,
                $weight,
                $candyId,
                $requiredCandyCount,
                $eggTravelLength,
                $spawnChance,
                $spawnTime,
                $minimumMultiplier,
                $maximumMultiplier,
                $previousEvolutionPokemonId,
                $nextEvolutionPokemonId,
                $types,
                $weaknesses
            );
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Some data isn't OK. (pokemon)");
        }
    }

    /**
     * Edits existing pokemon
     *
     * @param int $pokemonId Identification number
     * @param string $newOfficialNumber New number in official pokedex
     * @param string $newName New name
     * @param string $newImageUrl New URL to profile image file
     * @param float $newHeight New height (in meters)
     * @param float $newWeight New weight (in kilograms)
     * @param int|null $newCandyId New candy for evolution - identification number
     * @param int|null $newRequiredCandyCount New number of candies required for evolution
     * @param int|null $newEggTravelLength New length of way to travel with egg to birth
     * @param float $newSpawnChance New chance to spawn (percent in real number form)
     * @param string $newSpawnTime New time of most active spawning
     * @param float|null $newMinimumMultiplier New minimum multiplier of combat power
     * @param float|null $newMaximumMultiplier New maximum multiplier of combat power
     * @param int|null $newPreviousEvolutionPokemonId New previous evolution (pokemon) identification number
     * @param int|null $newNextEvolutionPokemonId New next evolution (pokemon) identification number
     * @param array $newTypes New types (advantages)
     * @param array $newWeaknesses New weaknesses
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function edit(
        int $pokemonId,
        string $newOfficialNumber,
        string $newName,
        string $newImageUrl,
        float $newHeight,
        float $newWeight,
        ?int $newCandyId,
        ?int $newRequiredCandyCount,
        ?int $newEggTravelLength,
        float $newSpawnChance,
        string $newSpawnTime,
        ?float $newMinimumMultiplier,
        ?float $newMaximumMultiplier,
        ?int $newPreviousEvolutionPokemonId,
        ?int $newNextEvolutionPokemonId,
        array $newTypes,
        array $newWeaknesses
    ): void {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        if (empty($newOfficialNumber) || empty($newName) || empty($newImageUrl) || empty($newHeight)
            || empty($newWeight)
            || empty($newSpawnTime)
            || empty($newTypes)
            || empty($newWeaknesses)) {
            throw new BadFormDataException("Some form filed hasn't been filled.");
        }

        if (!preg_match("%\d{3}%", $newOfficialNumber)) {
            throw new BadFormDataException("Official number format isn't valid.");
        }

        if (mb_strlen($newName) < 3) {
            throw new BadFormDataException("Pokemon's name is too short.");
        }

        if (!filter_var($newImageUrl, FILTER_VALIDATE_URL)) {
            throw new BadFormDataException("Image URL has bad format.");
        }
        if (getimagesize($newImageUrl)[0] === 0 || getimagesize($newImageUrl)[1] === 0) {
            throw new BadFormDataException("Image URL doesn't point to valid image.");
        }

        try {
            $newSpawnTime = new DateTime($newSpawnTime);
        } catch (Exception $e) {
            throw new  BadFormDataException("Spawn time format isn't valid.");
        }

        $pokemon = $this->dbPokemonRepository->getPokemonById($pokemonId);

        if ($pokemon === null) {
            throw new BadFormDataException("Specified pokemon doesn't exists.");
        }

        // Update
        $pokemon->setOfficialNumber($newOfficialNumber)
            ->setName($newName)
            ->setImageUrl($newImageUrl)
            ->setHeight($newHeight)
            ->setWeight($newWeight)
            ->setCandy($this->dbCandyRepository->getCandyById($newCandyId))
            ->setRequiredCandyCount($newRequiredCandyCount)
            ->setEggTravelLength($newEggTravelLength)
            ->setSpawnChance($newSpawnChance)
            ->setSpawnTime($newSpawnTime)
            ->setMinimumMultiplier($newMinimumMultiplier)
            ->setMaximumMultiplier($newMaximumMultiplier)
            ->setPreviousEvolution($this->dbPokemonRepository->getPokemonById($newPreviousEvolutionPokemonId))
            ->setNextEvolution($this->dbPokemonRepository->getPokemonById($newNextEvolutionPokemonId))
            ->setTypes($this->dbTypeRepository->getTypesByIds($newTypes))
            ->setWeaknesses($this->dbTypeRepository->getTypesByIds($newWeaknesses));

        try {
            $this->dbPokemonRepository->editPokemon($pokemon);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Some data isn't OK. (pokemon)");
        }
    }

    /**
     * Removes existing pokemon
     *
     * @param int $pokemonId Identification number
     *
     * @throws \App\Exceptions\BadFormDataException Bad data
     * @throws \App\Exceptions\InsufficientPermissionsException Insufficient permissions
     */
    public function remove(int $pokemonId): void
    {
        // Checks
        if ($this->personManager->isLoggedInPersonAdmin() === false) {
            throw new InsufficientPermissionsException("You have insufficient permissions to do this.");
        }

        // Remove
        try {
            $this->dbPokemonRepository->removePokemon($pokemonId);
        } catch (RepositoryDataManipulationException $e) {
            throw new BadFormDataException("Specified pokemon doesn't exists.");
        }
    }
}