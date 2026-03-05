<?php
// app/core/Router.php

class Router {
    private $routes = [];
    
    public function add($url, $controller, $action, $method = 'GET') {
        $this->routes[] = [
            'url' => $url,
            'controller' => $controller,
            'action' => $action,
            'method' => $method
        ];
    }
    
    public function dispatch($url, $method) {
        // Убираем GET параметры
        $url = strtok($url, '?');
        
        foreach ($this->routes as $route) {
            if ($route['url'] === $url && $route['method'] === $method) {
                $controllerFile = "../app/controllers/" . $route['controller'] . ".php";
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $route['controller']();
                    
                    if (method_exists($controller, $route['action'])) {
                        $controller->{$route['action']}();
                        return;
                    }
                }
            }
        }
        
        // 404
        header("HTTP/1.0 404 Not Found");
        echo "404 - Страница не найдена";
    }
}