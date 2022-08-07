<?php

class Router {
    public static $routes = [];

    public static function add($routes, $function, $method = 'get')
    {
        array_push(self::$routes, [
            'expression' => $routes, 
            'function'=> $function, 
            'method' => $method
        ]);
    }
    protected static function methodNotAllowed($method){
        echo "Метод ".strtoupper($method)." не доступен";
    }
    protected static function pathNotFound(){
        echo "Страница не найдена";
    }
    public static function run(){
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        if($parsed_url){
            $path = $parsed_url['path'];
        }else {
            $path = '/';
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;
        $route_match_found = false;
        
        foreach(self::$routes as $route){
            if(preg_match('#^'.$route['expression'].'$#', $path, $matches)){
               
                $path_match_found = true;
                if(strtolower($method) == strtolower($route['method'])){
                    array_shift($matches);
                    $route_match_found = true;
                    call_user_func_array($route['function'], $matches);
                    break;
                }
            }
        }
        if(!$route_match_found){
            if($path_match_found){
                header("HTTP/1.0 405 Method Not Allowed");
                self::methodNotAllowed($route['method']);
            }else{
                header("HTTP/1.0 404 Not Found");
                self::pathNotFound();
            }
        }
    }
}
