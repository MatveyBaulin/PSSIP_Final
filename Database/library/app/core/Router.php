<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $params = [];

    public function add($route, $controller, $action, $method = 'GET') {
        $this->routes[] = [
            'route' => $route,
            'controller' => $controller,
            'action' => $action,
            'method' => $method
        ];
    }

    public function dispatch($url, $method) {
        $url = $this->removeQueryString($url);
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToPattern($route['route']);
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                $this->params = $matches;
                
                $controllerClass = "\\App\\Controllers\\" . $route['controller'];
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    
                    if (method_exists($controller, $route['action'])) {
                        call_user_func_array([$controller, $route['action']], $this->params);
                        return;
                    }
                }
            }
        }
        
        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo "404 - Страница не найдена";
    }

    private function convertToPattern($route) {
        $pattern = preg_replace('/\//', '\\/', $route);
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[a-zA-Z0-9\-_]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    private function removeQueryString($url) {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            $url = $parts[0];
        }
        return $url;
    }
}
?>