-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema spse_pokedex
-- -----------------------------------------------------
-- Databáze pro projekt Pokedex na webové aplikace (3. ročník)

-- -----------------------------------------------------
-- Schema spse_pokedex
--
-- Databáze pro projekt Pokedex na webové aplikace (3. ročník)
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `spse_pokedex` DEFAULT CHARACTER SET utf8 ;
USE `spse_pokedex` ;

-- -----------------------------------------------------
-- Table `spse_pokedex`.`persons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`persons` (
  `person_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nick` VARCHAR(30) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(65) NOT NULL,
  `is_admin` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `birth` DATE NOT NULL,
  PRIMARY KEY (`person_id`),
  UNIQUE INDEX `nick_UNIQUE` (`nick` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`candies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`candies` (
  `candy_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`candy_id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`pokemons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`pokemons` (
  `pokemon_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `official_number` SMALLINT(3) ZEROFILL UNSIGNED NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `image_url` VARCHAR(100) NOT NULL,
  `height` FLOAT(5,2) UNSIGNED NOT NULL,
  `weight` FLOAT(6,2) UNSIGNED NOT NULL,
  `candy_id` INT UNSIGNED NULL,
  `required_candy_count` SMALLINT(3) UNSIGNED NULL,
  `egg_travel_length` TINYINT(2) UNSIGNED NULL,
  `spawn_chance` DECIMAL(6,4) UNSIGNED NOT NULL,
  `spawn_time` TIME NULL,
  `minimum_multiplier` DECIMAL(4,2) UNSIGNED NULL,
  `maximum_multiplier` DECIMAL(4,2) UNSIGNED NULL,
  `previous_evolution` INT UNSIGNED NULL,
  `next_evolution` INT UNSIGNED NULL,
  PRIMARY KEY (`pokemon_id`),
  UNIQUE INDEX `official_number_UNIQUE` (`official_number` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `image_url_UNIQUE` (`image_url` ASC),
  INDEX `fk_pokemons_candies1_idx` (`candy_id` ASC),
  INDEX `fk_pokemons_pokemons1_idx` (`previous_evolution` ASC),
  INDEX `fk_pokemons_pokemons2_idx` (`next_evolution` ASC),
  CONSTRAINT `fk_pokemons_candies1`
    FOREIGN KEY (`candy_id`)
    REFERENCES `spse_pokedex`.`candies` (`candy_id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pokemons_pokemons1`
    FOREIGN KEY (`previous_evolution`)
    REFERENCES `spse_pokedex`.`pokemons` (`pokemon_id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pokemons_pokemons2`
    FOREIGN KEY (`next_evolution`)
    REFERENCES `spse_pokedex`.`pokemons` (`pokemon_id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`types` (
  `type_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`pokemon_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`pokemon_types` (
  `pokemon_id` INT UNSIGNED NOT NULL,
  `type_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`pokemon_id`, `type_id`),
  INDEX `fk_pokemons_in_types_types1_idx` (`type_id` ASC),
  INDEX `fk_pokemons_in_types_pokemons_idx` (`pokemon_id` ASC),
  CONSTRAINT `fk_pokemons_in_types_pokemons`
    FOREIGN KEY (`pokemon_id`)
    REFERENCES `spse_pokedex`.`pokemons` (`pokemon_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pokemons_in_types_types1`
    FOREIGN KEY (`type_id`)
    REFERENCES `spse_pokedex`.`types` (`type_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`pokemon_weaknesses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`pokemon_weaknesses` (
  `pokemon_id` INT UNSIGNED NOT NULL,
  `type_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`pokemon_id`, `type_id`),
  INDEX `fk_pokemons_in_types_types2_idx` (`type_id` ASC),
  INDEX `fk_pokemons_in_types_pokemons1_idx` (`pokemon_id` ASC),
  CONSTRAINT `fk_pokemons_in_types_pokemons1`
    FOREIGN KEY (`pokemon_id`)
    REFERENCES `spse_pokedex`.`pokemons` (`pokemon_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pokemons_in_types_types2`
    FOREIGN KEY (`type_id`)
    REFERENCES `spse_pokedex`.`types` (`type_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spse_pokedex`.`persons_pokemons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spse_pokedex`.`persons_pokemons` (
  `pokemon_id` INT UNSIGNED NOT NULL,
  `person_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`pokemon_id`, `person_id`),
  INDEX `fk_pokemons_in_persons_persons1_idx` (`person_id` ASC),
  INDEX `fk_pokemons_in_persons_pokemons1_idx` (`pokemon_id` ASC),
  CONSTRAINT `fk_pokemons_in_persons_pokemons1`
    FOREIGN KEY (`pokemon_id`)
    REFERENCES `spse_pokedex`.`pokemons` (`pokemon_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pokemons_in_persons_persons1`
    FOREIGN KEY (`person_id`)
    REFERENCES `spse_pokedex`.`persons` (`person_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
