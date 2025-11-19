<?php

namespace App\Core;

class Router{

    private array $routes = [];

    // callable -> 呼び出し可能（コールバック可能）な値
    // $handler 引数には、関数やメソッドとして実行できるものでなければならない
    // void -> 「空 (void)」 を意味する戻り値の型宣言
    // 結果やデータなどを呼び出し元に返す必要がない場合に使用
    public function get(string $path, callable $handler): void{
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(string $method, string $path){

        $result = $this->match($method, $path);

        if ($result === null){
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        [$handler, $params] = $result;

        echo call_user_func($handler, $params);

        // 完全一致ルート
        // isset()
        // 変数が定義されているか、かつ、その値が NULL ではないかをチェックするための関数
        if (isset($this->routes[$method][$path])){

            //call_user_func()
            //phpのコールバック機能を実現するための関数、第一引数で指定した関数名または、メソッド名を呼び出す。
            return call_user_func($this->routes[$method][$path]);
        }

        // 動的ルートの判定
        foreach ($this->routes[$method] as $route => $handler){

            //preg_replace()
            //正規表現を使って文字列の検索と置換を行うための関数
            // 第一引数（検索する正規表現のパターン）、第二引数（置換後の文字列）、第三引数（検索・置換の対象となる文字列）
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            // preg_match()
            // 正規表現を使って文字列が特定のパターンに一致するかどうかをチェックする関数
            // $pattern:検索する正規表現のパターン
            // $path:検索対象の文字列
            // $matches: 一致した部分を格納するための配列変数
            if (preg_match($pattern, $path, $matches)){

                // array_shift()
                // 配列の先頭の要素を取り除き、その要素の値を返す関数
                array_shift($matches); //[0]は全文マッチなので捨てる

                // call_user_func_array()
                // 呼び出したい関数に渡す引数を配列で指定する
                // $handler:呼び出したい関数やメソッド
                // $matches:コールバック関数に渡したい引数の配列
                return call_user_func_array($handler, $matches);
            }

        }
    }
    
    public function post(string $path, callable $handler): void{
        $this->routes['POST'][$path] = $handler;
    }

    public function match($method, $path){
        foreach($this->routes[$method] as $route => $handler){
            //{id}などの動的パラメータを正規表現に変換
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if(preg_match($pattern, $path, $matches)){
                //数字は int にキャスト
                foreach($matches as $key => $value){
                    // ctype_digit(mixed $text): bool
                    // 与えられた文字列 text のすべての文字が 数字であるかどうかを調べる
                    if(ctype_digit($value)){
                        $matches[$key] = (int)$value;
                    }
                }
                return [$handler, $matches];
            }
        }
        return null;
    }
}
