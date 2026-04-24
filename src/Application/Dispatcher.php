<?php

namespace App\Application;

use App\Application\Utils\SessionManager;
use App\Controllers\ErrorsController;

class Dispatcher
{
    use SessionManager;
    protected Router $router;
    protected Request $request;

    public function __construct()
    {
        $this->initSession();
        $this->router = new Router();
        $this->request = new Request();
    }

    public function run(): void
    {
        $route = $this->router->match($this->request);

        if (!$route) {
            $controller = new ErrorsController($this->request);
        } else {
            if (!class_exists($route["controller"])) {
                $file = LOG . "messages.log";
                $message = sprintf("%s on line %s in %s\n", "class not exists", "29", "Dispatcher.php");
                error_log($message, 3, $file);
                $response = new Response(true);
                $response->send();
                exit();
            }

            $controller = new $route["controller"]($this->request);

            if (!method_exists($controller, $route["action"])) {
                $file = LOG . "messages.log";
                $message = sprintf("%s on line %s in %s\n", "method not exists", "40", "Dispatcher.php");
                error_log($message, 3, $file);
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