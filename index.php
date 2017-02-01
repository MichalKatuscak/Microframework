<?php

use Katuscak\Kernel\Factory;
use Katuscak\Component\Routing\Router;
use Katuscak\Component\Routing\Route;
use App\Controller\HomepageController;
use App\Controller\LoginController;

// Step 1 - Set autoloading
include_once __DIR__ . "/autoload.php";

// Step 2 - Set constants
define("KATUSCAK_DIR", __DIR__);
define("KATUSCAK_DIR_CONFIG", KATUSCAK_DIR . "/config");

// Step 3 - Set routes
$router = Factory::load(Router::class);
$router->addRoute(
    new Route("homepage", "/", [HomepageController::class, "index"]),
    new Route("login", "/login", [LoginController::class, "index"])
);