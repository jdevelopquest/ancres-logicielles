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
//            "/(guest|registered|moderator|admin)/",
//            "app\Controllers\ErrorsController",
//            "error404"
//        );
//
//        $this->add("/^(\/|\/public\/index\.php)$/",
//            "/ctr=errors&act=error500/",
//            "/^(GET|POST)$/",
//            "/(guest|registered|moderator|admin)/",
//            "app\Controllers\ErrorsController",
//            "error500"
//        );

        // home
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "index"
        );

        // posts indexSoftwares
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=indexSoftwares$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "indexSoftwares"
        );

        // posts showSoftware
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=showSoftware&id=\d+$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "showSoftware"
        );

        // supports about
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=about$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "about"
        );

        // supports policies
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=policies$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "policies"
        );

        // accounts signup
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=signup$/",
            "/^(GET|POST)$/",
            "/^(guest)$/",
            "App\Controllers\AccountsController",
            "signup"
        );

        // accounts login
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=login$/",
            "/^(GET|POST)$/",
            "/^(guest)$/",
            "App\Controllers\AccountsController",
            "login"
        );

        // accounts logout
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=logout$/",
            "/^(GET|POST)$/",
            "/^(registered|moderator|admin)$/",
            "App\Controllers\AccountsController",
            "logout"
        );

        // users api saveTheme
        $this->add("/^(\/|\/public\/index\.php)$/",
            "/^ctr=users&act=saveTheme$/",
            "/^(POST)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\UsersApiController",
            "saveTheme"
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

            if (!preg_match($route['queryPattern'], $request->getQuery())) {
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