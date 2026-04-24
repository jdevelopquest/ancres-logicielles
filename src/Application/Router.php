<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

class Router
{
    use SessionManager;

    private array $routes = [];

    /**
     * Constructor method for initializing routes.
     *
     * This method sets up the routing for various controllers and their methods by defining
     * URL patterns, HTTP methods, and access roles required to execute specific actions.
     * It ensures that requests are routed to their corresponding controllers and methods.
     *
     * @return void
     */
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

    /**
     * Adds a route to the routes array with the specified parameters.
     *
     * @param bool $isAjax Indicates whether the route is for an AJAX request.
     * @param string $pathPattern The pattern for matching the URL path.
     * @param string $queryPattern The pattern for matching the query string.
     * @param string $methodPattern The pattern for matching the HTTP method.
     * @param string $rolePattern The pattern for matching the user role.
     * @param string $controller The controller associated with the route.
     * @param string $action The action or method to be invoked within the controller.
     *
     * @return void
     */
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

    /**
     * Matches a given request against the defined routes and returns the matching route.
     *
     * @param Request $request The request object to be matched against the route patterns.
     * @return array|bool Returns the matching route as an array if found, or false if no route matches.
     */
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