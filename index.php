<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

use Katuscak\Kernel\Factory;
use Katuscak\Component\Routing\Router;
use Katuscak\Component\Routing\Route;

include_once __DIR__ . "/bootstrap.php";

$router = Factory::load(Router::class);
$router->addRoute(
    new Route("homepage", "/", [\App\Controller\HomepageController::class, "index"]),
    new Route("login", "/login", [\App\Controller\LoginController::class, "index"])
);