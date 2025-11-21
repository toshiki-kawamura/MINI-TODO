<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\HomeController;

// HTTPリクエストを受け取る

// Routerインスタンス作成
$router = new Router();

// Controllerインスタンス作成
$controller = new HomeController();

// ホーム画面（一覧の表示）
$router->get('/', [$controller, 'index']);

// タスク追加
$router->post('/tasks/add', [$controller, 'add']);

// タスク完了
$router->post('/tasks/{id}/done', [$controller, 'done']);

// タスク未完了に戻す
$router->post('/tasks/{id}/undo', [$controller, 'undo']);

// タスク削除
$router->post('/tasks/{id}/delete', [$controller, 'delete']);

//
// リクエストの解析
//

// HTTPメソッド
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// URL（クエリ ?xxx は除去してパスのみ取得）
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// ルーターで処理実行
$router->dispatch($method, $uri);