<?php

declare(strict_types = 1);

/**
 * Index file
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 */

use App\Exceptions\PageNotFoundException;
use App\Routing\Router;

require_once '../src/starter.php';

/**
 * @var \App\Routing\Router $router
 */
$router = $container->getInstance(Router::class);

try {
    require_once $router->loadUrl($_SERVER['REQUEST_URI']);
} catch (PageNotFoundException $e) {
    echo "<h1>Tato stránka neexistuje.</h1>";
}