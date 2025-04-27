<?php

namespace App\Application;

use App\Controllers\ErrorsController;

class Dispatcher
{
    protected Router $router;
    protected Request $request;

    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
    }

    public function run(): void
    {
        $route = $this->router->match($this->request);

        if (!$route) {
            $controller = new ErrorsController();
        } else {
            if (!class_exists($route["controller"])) {
                $response = new Response(true);
                $response->send();
                exit();
            }

            $controller = new $route["controller"]();

            if (!method_exists($controller, $route["action"])) {
                $response = new Response(true);
                $response->send();
                exit();
            }
        }

        $action = $controller instanceof ErrorsController ? "error404" : $route["action"];

        $response = $controller->$action();

        $response->send();
    }
}