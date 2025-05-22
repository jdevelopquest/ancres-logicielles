<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

class Router
{
    use SessionManager;

    private array $routes = [];

    public function __construct()
    {
        // home
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "indexSoftwares"
        );

        // posts indexSoftwares
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=indexSoftwares$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "indexSoftwares"
        );

        // posts showSoftware
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=showSoftware&id=\d+$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\PostsController",
            "showSoftware"
        );

        // posts unpublish
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=unpublish$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "unpublish"
        );

        // posts publish
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=publish$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "publish"
        );

        // posts ban
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=ban$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "ban"
        );

        // posts unban
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=unban$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "unban"
        );

        // posts updatePostboxModTool
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=updatePostboxModTool$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "updatePostboxModTool"
        );

        // posts updateSoftwareStatus
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=posts&act=updateSoftwareStatus$/",
            "/^(POST)$/",
            "/^(moderator|admin)$/",
            "App\Controllers\PostsController",
            "updateSoftwareStatus"
        );

        // supports about
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=about$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "about"
        );

        // supports policies
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=supports&act=policies$/",
            "/^(GET)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\SupportsController",
            "policies"
        );

        // accounts signup
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=signup$/",
            "/^(GET|POST)$/",
            "/^(guest)$/",
            "App\Controllers\AccountsController",
            "signup"
        );

        // accounts login
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=login$/",
            "/^(GET|POST)$/",
            "/^(guest)$/",
            "App\Controllers\AccountsController",
            "login"
        );

        // accounts logout
        $this->add(
            false,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=accounts&act=logout$/",
            "/^(GET|POST)$/",
            "/^(registered|moderator|admin)$/",
            "App\Controllers\AccountsController",
            "logout"
        );

        // users api saveTheme
        $this->add(
            true,
            "/^(\/|\/public\/index\.php)$/",
            "/^ctr=users&act=saveTheme$/",
            "/^(POST)$/",
            "/^(guest|registered|moderator|admin)$/",
            "App\Controllers\SessionsController",
            "saveTheme"
        );
    }

    private function add(
        bool $isAjax,
        string $pathPattern,
        string $queryPattern,
        string $methodPattern,
        string $rolePattern,
        string $controller,
        string $action): void
    {
        $this->routes[] = [
            "isAjax" => $isAjax,
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
//            if (!preg_match($route["pathPattern"], $request->getPath())) {
//                continue;
//            }

            if (!preg_match($route["queryPattern"], $request->getQuery())) {
                continue;
            }

            return $route;
        }

        return false;
    }
}