<?php

namespace App\Core;

// 適切なコントローラーに振り分ける
class Router{

    // ルート格納用
    private array $routes = [];

    // GETメソッドのルート登録
    public function get(string $path, callable $handler): void{
        $this->routes['GET'][$path] = $handler;
    }

    // POSTメソッドのルート登録
    public function post(string $path, callable $handler): void{
        $this->routes['POST'][$path] = $handler;
    }

    // 実際のリクエストを振り分ける関数
    public function dispatch(string $method, string $path){

        // strtoupper(string $string): string
        // 文字列内のアルファベット小文字をすべて大文字に変換する
        $method = strtoupper($method);
        $routes = $this->routes[$method] ?? [];

        // -------------------------
        // ① 完全一致ルートのチェック
        // -------------------------
        if (isset($routes[$path])){
            $handler = $routes[$path];
            return $this->callHandler($handler, []);
        }

        // -------------------------
        // ② {id} などを含む“動的ルート”のチェック
        // -------------------------
        foreach ($routes as $route => $handler){

            // {id} → ([^/]+) に変換し正規表現パターン化する
            // 例: /tasks/{id}/done → #^/tasks/([^/]+)/done$#
            $pattern = preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            // 正規表現にマッチするか？
            if (preg_match($pattern, $path, $matches)){
                $params = [];

                foreach($matches as $key => $val){
                    if (!is_int($key)){
                        // ctype_digit(string $string): bool
                        // 文字列内のすべての文字が10進数の数字（0から9）であるかどうかをチェック
                        // 数字ならintにキャスト
                        $params[$key] = ctype_digit($val) ? (int)$val : $val;
                    }
                }
                // Controller に渡す
                return $this->callHandler($handler, $params);
            }

        }
        // どのルートにも一致しなかった場合
        http_response_code(404);
        echo "404 Not Found";
    }
    
    // 実際に Controller のメソッドを呼び出す
    private function callHandler(callable $handler, array $params){
        // call_user_func(callable $callback, mixed ...$args): mixed
        // 指定されたコールバックを実行し、その結果を返します。
        return call_user_func($handler, $params);
    }
}
