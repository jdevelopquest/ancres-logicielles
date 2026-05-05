<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private static array $routes = [];

    private function __construct() {}

    /**
     * Adds a route to the route array.
     *
     * @param Route $route
     * @return void
     */
    public static function add(Route $route): void
    {
        self::$routes[] = $route;
    }

    /**
     * Matches a given request against the defined routes and returns the matching route.
     *
     * @param Request $request The request objects to be matched against the route patterns.
     * @return Route|bool Returns the matching route as a Route if found, or false if no route matches.
     */
    public static function match(Request $request): Route|bool
    {
        foreach (self::$routes as $route) {
            // Vérifier d'abord le chemin
            if (!preg_match($route->pathPattern, $request->path)) {
                continue;
            }

            // Puis la requête
            if (!preg_match($route->queryPattern, $request->query)) {
                continue;
            }

            return $route;
        }

        return false;
    }
}
