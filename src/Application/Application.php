<?php

namespace App\Application;

use App\Application\Utils\SessionManager;
use Exception;

/**
 * Represents the main application class responsible for handling
 * the lifecycle of a request and response.
 *
 * Provides functionality to manage sessions and execute the dispatcher
 * to process the current request using the associated response object.
 */
class Application
{
    use SessionManager;
    private Request $request;
    private Response $response;
    private Router $router;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->initSession();
        $routeDefinitions = require CONFIG_PATH . "routes.php";
        if (is_array($routeDefinitions)) {
            $this->router = new Router($routeDefinitions);
        } else {
            throw new Exception("Routes file is not an array");
        }
    }

    /**
     * Executes the dispatcher with the current request and response objects.
     *
     * @return void
     */
    public function run(): void
    {
        $dispatcher = new Dispatcher($this->request, $this->response, $this->router);
        $dispatcher->run();
    }
}