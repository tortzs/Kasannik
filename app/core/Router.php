<?php

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$this->normalizePath($path)] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = $this->normalizePath($path);

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        $matchedAction = null;
        $params = [];

        foreach ($this->routes[$method] as $routePath => $action) {
            $match = $this->matchRoute($routePath, $path);

            if ($match !== false) {
                $matchedAction = $action;
                $params = $match;
                break;
            }
        }

        if ($matchedAction === null) {
            $this->notFound();
            return;
        }

        [$controllerName, $methodName] = explode('@', $matchedAction);

        if (!class_exists($controllerName)) {
            echo "Controller $controllerName nie istnieje";
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            echo "Metoda $methodName nie istnieje w $controllerName";
            return;
        }

        $controller->$methodName(...$params);
    }

    private function normalizePath(string $path): string
    {
        $path = rtrim($path, '/');

        if ($path === '') {
            return '/';
        }

        return $path;
    }

    private function matchRoute(string $routePath, string $currentPath): array|false
    {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)}/', $routePath, $paramNames);

        $pattern = preg_quote($routePath, '#');

        $pattern = preg_replace(
            '#\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}#',
            '([^/]+)',
            $pattern
        );

        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $currentPath, $matches)) {
            return false;
        }

        array_shift($matches);

        return array_map('urldecode', $matches);
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "404 - Strona nie istnieje";
    }
}