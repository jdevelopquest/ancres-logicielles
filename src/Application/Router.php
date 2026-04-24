<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

class Router
{
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
    public function __construct(array $routeDefinitions = [])
    {
        foreach ($routeDefinitions as $routeDefinition) {
            extract($routeDefinition);
            $this->add($isAjax, $pathPattern, $queryPattern, $methodPattern, $rolePattern, $controller, $action);
        }
    }

    /**
     * Adds a route to the route array with the specified parameters.
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
    public function add(
        bool   $isAjax,
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

            if (!preg_match($route["queryPattern"], $request->query)) {
                continue;
            }

            return $route;
        }

        return false;
    }
}