<?php
declare(strict_types=1);

namespace App\Core;

readonly class Route
{
    /*
     * @param bool $isAjax Indicates whether the route is for an AJAX request.
     * @param string $pathPattern The pattern for matching the URL path.
     * @param string $queryPattern The pattern for matching the query string.
     * @param string $methodPattern The pattern for matching the HTTP method.
     * @param string $rolePattern The pattern for matching the user role.
     * @param string $controller The controller associated with the route.
     * @param string $action The action or method to be invoked within the controller.
     */
    public function __construct(
        public bool $isAjax,
        public string $pathPattern,
        public string $queryPattern,
        public string $methodPattern,
        public string $rolePattern,
        public string $controller,
        public string $action,
    ) {}
}
