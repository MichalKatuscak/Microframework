<?php

use Katuscak\Kernel\Factory;
use Katuscak\Component\Routing\Router;
use Katuscak\Component\Routing\Route;
use App\Controller\HomepageController;
use App\Controller\LoginController;

include_once __DIR__ . "/bootstrap.php";

$router = Factory::load(Router::class);
$router->addRoute(
    new Route("homepage", "/", [HomepageController::class, "index"]),
    new Route("login", "/login", [LoginController::class, "index"])
);