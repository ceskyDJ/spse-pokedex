<?php

/**
 * Starter
 * Start default actions and system base
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

declare(strict_types = 1);

use App\DI\DIContainer;
use App\Models\Configurator;
use JanDrabek\Tracy\GitVersionPanel;
use Tracy\Debugger;

// Show errors, warnings, notices
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Class autoloading
require_once __DIR__.'/../vendor/autoload.php';

// Configs and auto run services
session_start();
mb_internal_encoding("UTF-8");

// Start Tracy
Debugger::enable(Debugger::DETECT, null, "admin@ceskydj.cz");
Debugger::getBar()
    ->addPanel(new GitVersionPanel());

// Create config manager and configure Tracy
$configurator = new Configurator("src/Config/base-config.ini", "src/Config/local-config.ini");
Debugger::$logDirectory = $configurator->getLogDir();
Debugger::$productionMode = !$configurator->isActualServerDevelopment();

// DI container
$container = $configurator->createContainer();