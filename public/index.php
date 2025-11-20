<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\HomeController;

$router = new Router();
$controller = new HomeController();

$router->get('/', [$controller, 'index']);

$router->post('/tasks/add', [$controller, 'add']);

$router->post('/tasks/{id}/done', [$controller, 'done']);

$router->post('/tasks/{id}/undo', [$controller, 'undo']);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$router->dispatch($method, $uri);