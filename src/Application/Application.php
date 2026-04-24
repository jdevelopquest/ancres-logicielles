<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

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

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->initSession();
    }

    /**
     * Executes the dispatcher with the current request and response objects.
     *
     * @return void
     */
    public function run(): void
    {
        $dispatcher = new Dispatcher($this->request, $this->response);
        $dispatcher->run();
    }
}