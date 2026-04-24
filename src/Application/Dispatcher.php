<?php

namespace App\Application;

use App\Application\Utils\SessionManager;
use App\Controllers\ErrorsController;
use Exception;

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

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $route = $this->router->match($this->request);

        if (!$route) {
            $controller = new ErrorsController($this->request);
        } else {
            if (!class_exists($route["controller"])) {
                $message = sprintf("%s on line %s in %s\n", "class not exists", "29", "Dispatcher.php");
                throw new Exception($message);
            }

            $controller = new $route["controller"]($this->request);

            if (!method_exists($controller, $route["action"])) {
                $message = sprintf("%s on line %s in %s\n", "method not exists", "40", "Dispatcher.php");
                throw new Exception($message);
            }
        }

        $action = $controller instanceof ErrorsController ? "error404" : $route["action"];

        $response = $controller->$action();

        $response->send();
    }
}