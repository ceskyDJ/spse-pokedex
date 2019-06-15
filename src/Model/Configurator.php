<?php

namespace App\Models;

use App\DI\DIContainer;
use App\Exceptions\NoConfigFileGivenException;
use App\Exceptions\NonExistingFileException;
use App\Exceptions\NotSetAllDataInLocalConfigException;
use App\Model\Common\Model;
use function array_replace_recursive;
use function file_exists;
use function implode;
use function in_array;
use Nette\Caching\Storages\FileStorage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\Conventions\DiscoveredConventions;
use Nette\Database\Structure;
use function parse_ini_file;
use const INI_SCANNER_TYPED;

/**
 * Config manager
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Model
 */
class Configurator extends Model
{

    /**
     * @var string[] configFiles Config files
     */
    private $configFiles;
    /**
     * @var array configs Loaded configs
     */
    private $configs;
    /**
     * @var string tempDir Temp file dir
     */
    private $tempDir;
    /**
     * @var string logDir Log file dir
     */
    private $logDir;

    /**
     * Configurator constructor
     *
     * @param string[] configFile $configFile Addresses of config files
     *
     * @throws \App\Exceptions\NoConfigFileGivenException No config file specified
     * @throws \App\Exceptions\NonExistingFileException Invalid file address
     * @throws \App\Exceptions\NotSetAllDataInLocalConfigException Missing config in local config file
     */
    public function __construct(...$configFiles)
    {
        $this->configFiles = $configFiles;

        $this->configs = $this->getConfigs();
        $this->setDirectoriesFromConfigs();
    }

    /**
     * Returns system configs from saved config files
     *
     * @return array Configs
     * @throws \App\Exceptions\NoConfigFileGivenException No config file specified
     * @throws \App\Exceptions\NonExistingFileException Invalid file address
     * @throws \App\Exceptions\NotSetAllDataInLocalConfigException Missing config in local config file
     */
    private function getConfigs(): array
    {
        if (empty($this->configFiles)) {
            throw new NoConfigFileGivenException("No config file has been set.");
        }

        $configs = [];
        foreach ($this->configFiles as $file) {
            // Config file doesn't exists
            if (!file_exists("../$file")) {
                throw new NonExistingFileException("Entered file address is invalid");
            }

            $actualFile = parse_ini_file("../$file", true, INI_SCANNER_TYPED);

            if (empty($configs)) {
                $configs = $actualFile;
            } else {
                $configs = array_replace_recursive($configs, $actualFile);
            }
        }

        // Option overriding control in config files
        // default values style: <...>
        foreach ($configs as $section) {
            if (($notSet = preg_grep("%^<(.*)>$%", $section))) {
                throw new NotSetAllDataInLocalConfigException(
                    "This default config value hasn't been replaced: ".implode(", ", $notSet)
                );
            }
        }

        return $configs;
    }

    /**
     * Sets up paths from configs
     */
    private function setDirectoriesFromConfigs(): void
    {
        $this->tempDir = "../{$this->configs['paths']['temp']}";
        $this->logDir = "../{$this->configs['paths']['log']}";
    }

    /**
     * Creates a DI container and add base functions to it
     *
     * @return \App\DI\DIContainer DI container
     */
    public function createContainer(): DIContainer
    {
        $container = new DIContainer();

        // Databáze
        $dbConfig = $this->getDatabaseConfig();
        $dbStorage = new FileStorage($this->getTempDir());
        $dbConnection = new Connection("mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}", $dbConfig['user-name'], $dbConfig['user-password']);
        $dbStructure = new Structure($dbConnection, $dbStorage);
        $dbConventions = new DiscoveredConventions($dbStructure);
        $dbContext = new Context($dbConnection, $dbStructure, $dbConventions, $dbStorage);

        return $container->addInstance($dbContext)
            ->addInstance($this)
            ->addInstance($container);
    }

    /**
     * Gets database config
     *
     * @return array Database config
     */
    public function getDatabaseConfig(): array
    {
        return $this->configs['database'];
    }

    /**
     * Getter for tempDir
     *
     * @return string
     */
    public function getTempDir(): string
    {
        return $this->tempDir;
    }

    /**
     * Fluent setter for tempDir
     *
     * @param string $tempDir
     *
     * @return Configurator
     */
    public function setTempDir(string $tempDir): Configurator
    {
        $this->tempDir = "../$tempDir";

        return $this;
    }

    /**
     * Getter for logDir
     *
     * @return string
     */
    public function getLogDir(): string
    {
        return $this->logDir;
    }

    /**
     * Fluent setter for logDir
     *
     * @param string $logDir
     *
     * @return Configurator
     */
    public function setLogDir(string $logDir): Configurator
    {
        $this->logDir = "../$logDir";

        return $this;
    }

    /**
     * Checks if current server is development
     *
     * @return bool Is actual server development?
     */
    public function isActualServerDevelopment(): bool
    {
        return in_array($_SERVER['SERVER_NAME'], $this->configs['development-server']);
    }
}