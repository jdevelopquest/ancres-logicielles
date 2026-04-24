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

    public function run(): void
    {
        $route = $this->router->match($this->request);

        // todo
        // il faut une response erreur en json pour les requêtes ajax

        if (!$route) {
            $controller = new ErrorsController($this->request);
        } else {
            try {
                if (!class_exists($route["controller"])) {
                    $message = sprintf("%s on line %s in %s\n", "class not exists", "34", "Dispatcher.php");
                    throw new Exception($message);
                }

                $controller = new $route["controller"]($this->request);

                if (!method_exists($controller, $route["action"])) {
                    $message = sprintf("%s on line %s in %s\n", "method not exists", "40", "Dispatcher.php");
                    throw new Exception($message);
                }
            } catch (Exception $exception) {
                $errorsController = new ErrorsController($this->request);
                $errorsController->error503()->send();
                exit();
            }
        }

        $action = $controller instanceof ErrorsController ? "error404" : $route["action"];

        // Tout est bon, si la méthode néssécite un argument
        // l'argument est récupéré depuis $_GET
        $response = isset($_GET["id"]) ? $controller->$action($_GET["id"]) : $controller->$action();

        $response->send();
    }
}