<?php

namespace App\Core;

class Router{

    private array $routes = [];
    public function get(string $path, callable $handler): void{
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void{
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path){
        $method = strtoupper($method);
        $routes = $this->routes[$method] ?? [];

        if (isset($routes[$path])){
            $handler = $routes[$path];
            return $this->callHandler($handler, []);
        }

        foreach ($routes as $route => $handler){
            
            // {id}, {slug} などのパラメータ名を抽出
            preg_match_all('#\{([^/]+)\}#', $route, $paramNames);

            $pattern = preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)){
                $params = [];

                foreach($matches as $key => $val){

                    if (!is_int($key)){
                        $params[$key] = ctype_digit($val) ? (int)$val : $val;
                    }
                }
                return $this->callHandler($handler, $params);
            }

        }
        http_response_code(404);
        echo "404 Not Found";
    }
    

    private function callHandler(callable $handler, array $params){
        return call_user_func($handler, $params);
    }
}
