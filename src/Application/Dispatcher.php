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

    public function __construct(protected Request $request, protected Response $response)
    {
        $this->router = new Router();
    }

    /**
     * Executes the routing process by matching the incoming request to a defined route
     * and delegates the execution to the corresponding controller and action.
     * Performs various validations such as AJAX compatibility, HTTP method, user roles,
     * token validation (for POST requests), and existence of the specified controller and action.
     * Handles errors by triggering appropriate error responses and logging diagnostic information.
     *
     * @return void
     */
    public function run(): void
    {
        $route = $this->router->match($this->request);

        if (!$route) {
            $this->logMessage("Pas de route");
            $this->logData($this->request);

            $this->triggerError404();
            exit();
        } else {
            // todo affiner le code erreur
            if ($route["isAjax"] !== $this->request->isAjax()) {
                $this->logMessage("La requête n'est pas ajax");
                $this->logData($this->request);

                $this->triggerError404();
                exit();
            }

            if (!preg_match($route["methodPattern"], $this->request->getMethod())) {
                $this->logMessage("Mauvais methode");
                $this->logData($this->request);

                $this->triggerError404();
                exit();
            }

            if (!preg_match($route["rolePattern"], $this->getUserRole())) {
                $this->logMessage("Mauvais role");
                $this->logData($this->request);

                $this->triggerError404();
                exit();
            }

            // si la methode est post, il faut contrôler le jeton
            if ($this->request->isPost() && !$this->isValidToken()) {
                $this->logMessage("Jeton incorrect");
                $this->logData($this->request);

                // todo peut-être indiquer à l'utilisateur qu'il faut recharger la page

                $this->triggerError503();
                exit();
            }

            try {
                if (!class_exists($route["controller"])) {
                    $message = sprintf("%s class not exists on line %s in %s\n", $route["controller"], "56", "Dispatcher.php");
                    throw new Exception($message);
                }

                $controller = new $route["controller"]($this->request, $this->response);

                if (!method_exists($controller, $route["action"])) {
                    $message = sprintf("Inside class %s method not exists %s on line %s in %s\n", $route["controller"], $route["action"], "63", "Dispatcher.php");
                    throw new Exception($message);
                }

                // Tout est bon, si la méthode néssécite un argument, il faut lui passer
                $action = $route["action"];
                $response = isset($this->request->getParams()["id"]) ? $controller->$action($this->request->getParams()["id"]) : $controller->$action();
                // ajout du jeton
                $this->setSessionToken();
                $response->send();
                exit();
            } catch (Exception $exception) {
                $this->logMessage($exception->getMessage());
                $this->logMessage($exception->getTraceAsString());
                $this->logData($this->request);

                $this->triggerError503();
                exit();
            }
        }
    }

    /**
     * Handles triggering a 404 error response.
     *
     * Depending on the type of request, it sends either an AJAX-specific 404 error response
     * or a standard 404 error response. Terminates the script execution after sending the response.
     *
     * @return void
     */
    private function triggerError404(): void
    {
        $errorsController = new ErrorsController($this->request, $this->response);
        if ($this->request->isAjax()) {
            $errorsController->error404ByAjax()->send();
        } else {
            $errorsController->error404()->send();
        }
    }

    /**
     * Triggers a 503-Service Unavailable error response and terminates the script execution.
     * Redirects the response to different error handling methods based on whether the request is an AJAX call or not.
     *
     * @return void
     */
    private function triggerError503(): void
    {
        $errorsController = new ErrorsController($this->request, $this->response);
        if ($this->request->isAjax()) {
            $errorsController->error503ByAjax()->send();
        } else {
            $errorsController->error503()->send();
        }
    }
}