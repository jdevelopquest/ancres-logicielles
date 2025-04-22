<?php

namespace App\Application;

class Dispatcher
{
    public function __construct(private Router $router)
    {

    }

    public function handle(string $path): void
    {
        $params = $this->router->match($path);

        if ($params === false)
        {
            exit("404 Not Found");
        }

        $action = $params['action'];
        $controller = 'App\\Controllers\\' . $params['controller'];

        $controller_object = new $controller(new Viewer());
        $controller_object->$action();
    }
}