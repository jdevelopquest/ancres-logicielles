<?php

namespace App\Application;

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
        $route = $this->router->match($this->request->getPath());

        if (!$route) {
            $route = $this->router->match("/error/404");
        }

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

        $response = $controller->{$route["action"]}();

        $response->send();
    }
}