<?php

namespace App\Application;

use App\Application\Utils\LogPrinter;
use App\Application\Utils\SessionManager;
use App\Controllers\ErrorsController;
use Exception;

class Dispatcher
{
    use SessionManager;
    use LogPrinter;

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
            $this->logMessage("Pas de route");
            $this->logData($this->request);
            $this->triggerError404();
        } else {
            // todo
            // affiner le code erreur
            if ($route["isAjax"] !== $this->request->isAjax()) {
                $this->logMessage("La requête n'est pas ajax");
                $this->logData($this->request);
                $this->triggerError404();
            }

            if (!preg_match($route["methodPattern"], $this->request->getMethod())) {
                $this->logMessage("Mauvais methode");
                $this->logData($this->request);
                $this->triggerError404();
            }

            if (!preg_match($route["rolePattern"], $this->getUserRole())) {
                $this->logMessage("Mauvais role");
                $this->logData($this->request);
                $this->triggerError404();
            }

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

                // Tout est bon, si la méthode néssécite un argument, il faut lui passer
                $action = $route["action"];
                $response = isset($this->request->getParams()["id"]) ? $controller->$action($this->request->getParams()["id"]) : $controller->$action();
                $response->send();
            } catch (Exception $exception) {
                $this->logMessage($exception->getMessage());
                $this->logMessage($exception->getTraceAsString());
                $this->logData($this->request);
                $errorsController = new ErrorsController($this->request);
                $errorsController->error503()->send();
                exit();
            }
        }
    }

    private function triggerError404(): void
    {
        $errorsController = new ErrorsController($this->request);
        if ($this->request->isAjax()) {
            $errorsController->error404ByAjax()->send();
        } else {
            $errorsController->error404()->send();
        }
    }
}