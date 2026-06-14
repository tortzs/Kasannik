<?php

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        if (!isset($this->routes[$method][$path])) {
           
            http_response_code(404);
            echo "404 - Strona nie istnieje";
            return;
        }

        $action = $this->routes[$method][$path];

        [$controllerName, $methodName] = explode('@', $action);

        if (!class_exists($controllerName)) {
            echo "Controller $controllerName nie istnieje";
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            echo "Metoda $methodName nie istnieje w $controllerName";
            return;
        }

        $controller->$methodName();
    }
}