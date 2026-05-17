<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helpers.php';

use Framework\Router;
use Framework\Session;

// Start session before routing
Session::start();

$router = new Router();

$routes = require_once basePath('routes.php');
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path /WS03/public from the URI
$basePath = '/WS03/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// If URI is empty, set it to /
if (empty($uri) || $uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
