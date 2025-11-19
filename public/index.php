<?php

use App\Core\Router;
use App\Controller\HomeController;

require __DIR__ . '/../vendor/autoload.php';

$router = new Router();
$controller = new HomeController();

//ルート定義
// [$controller, 'index'] -> クラスのメソッドをコールバックとして渡している
$router->get('/', [$controller, 'index']);
$router->get('/hello', [$controller, 'hello']);

//タスクを読み込む
$router->get('/tasks/{id}', [$controller, 'task']);

//タスクを追加する
$router->post('/tasks/add', [$controller, 'add']);

//タスクを完了にする
$router->post('/tasks/{id}/done', [$controller, 'done']);

//Dispatcher
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);