<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

class Router
{
    use SessionManager;
    private array $routes = [];

    public function __construct()
    {
//        $this->add("/^(\/|\/public\/index\.php)$/",
//            "/ctr=errors&act=error404/",
//            "/^(GET|POST)$/",
//            "/(guest|regular|moderator|admin)/",
//            "app\Controllers\ErrorsController",
//            "error404"
//        );
//
//        $this->add("/^(\/|\/public\/index\.php)$/",
//            "/ctr=errors&act=error500/",
//            "/^(GET|POST)$/",
//            "/(guest|regular|moderator|admin)/",
//            "app\Controllers\ErrorsController",
//            "error500"
//        );

        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^$/",
            "/^(GET)$/",
            "/^(guest|regular|moderator|admin)$/",
            "App\Controllers\ArticlesController",
            "index"
        );

        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=articles&act=index$/",
            "/^(GET)$/",
            "/^(guest|regular|moderator|admin)$/",
            "App\Controllers\ArticlesController",
            "index"
        );

        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=articles&act=show&id=\d+$/",
            "/^(GET)$/",
            "/^(guest|regular|moderator|admin)$/",
            "App\Controllers\ArticlesController",
            "show"
        );

        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=about$/",
            "/^(GET)$/",
            "/^(guest|regular|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "about"
        );

        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=policies$/",
            "/^(GET)$/",
            "/^(guest|regular|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "policies"
        );
    }

    private function add(string $pathPattern, string $queryPattern, string $methodPattern, string $rolePattern, string $controller, string $action): void
    {
        $this->routes[] = [
            "pathPattern" => $pathPattern,
            "queryPattern" => $queryPattern,
            "methodPattern" => $methodPattern,
            "rolePattern" => $rolePattern,
            "controller" => $controller,
            "action" => $action
        ];
    }

    public function match(Request $request): array|bool
    {
        foreach ($this->routes as $route) {
//            if (!preg_match($route['pathPattern'], $request->getPath())) {
//                continue;
//            }

            if (!preg_match($route['queryPattern'], $request->getQuery() ?? "")) {
                continue;
            }

            if (!preg_match($route['methodPattern'], $request->getMethod())) {
                continue;
            }

            if (!preg_match($route['rolePattern'], $this->getUserRole())) {
                continue;
            }

            return $route;
        }

        return false;
    }
}