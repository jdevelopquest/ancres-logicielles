<?php

namespace App\Application;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->add("all", "all", "/error/404", "App\Controllers\ErrorsController", "error404", null);
        $this->add("all", "all", "/error/505", "App\Controllers\ErrorsController", "error500", null);
        $this->add("all", "GET", "/", "App\Controllers\ArticlesController", "index", null);
        $this->add("all", "GET", "/articles/index", "App\Controllers\ArticlesController", "index", null);
    }

    private function add(string $role, string $method, string $path, string $controller, string $action, ?array $params = null): void
    {
        $this->routes[] = [
            "role" => $role,
            "method" => $method,
            "path" => $path,
            "controller" => $controller,
            "action" => $action,
            "params" => $params
        ];
    }

    public function match(string $path): array|bool
    {
        foreach ($this->routes as $route) {
            if ($route["path"] === $path) {
                return $route;
            }
        }

        return false;
    }
}