<?php
declare(strict_types=1);

namespace App\Application;

use App\Application\Utils\Logger;
use App\Application\Utils\SessionManager;
use App\Controllers\ErrorsController;
use Exception;

class Dispatcher
{
    use SessionManager;

    /**
     * Constructor for initializing the class with the required dependencies.
     *
     * @param Request $request The request object containing information about the HTTP request.
     * @param Response $response The response object used for sending HTTP responses.
     * @param Router $router The router object responsible for handling route resolution.
     *
     * @return void
     */
    public function __construct(protected Request $request, protected Response $response, protected Router $router)
    {
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
            $log = new Logger();
            $log->debug("No route found for requested path", ["path" => $this->request->path, "query" => $this->request->query]);
            $this->triggerError404();
            exit();
        } else {
            // todo affiner le code erreur
            if ($route["isAjax"] !== $this->request->isAjax()) {
                $log = new Logger();
                $log->debug("Not an AJAX request", ["path" => $this->request->path, "query" => $this->request->query]);
                $this->triggerError404();
                exit();
            }

            if (!preg_match($route["methodPattern"], $this->request->method)) {
                $log = new Logger();
                $log->debug("Bad method", ["path" => $this->request->path, "query" => $this->request->query, "method" => $this->request->method]);
                $this->triggerError404();
                exit();
            }

            if (!preg_match($route["rolePattern"], $this->getUserRole())) {
                $log = new Logger();
                $log->debug("Bad role", ["path" => $this->request->path, "query" => $this->request->query, "role" => $this->getUserRole()]);
                $this->triggerError404();
                exit();
            }

            // si la methode est post, il faut contrôler le jeton
            if ($this->request->isPost() && !$this->isValidToken()) {
                $log = new Logger();
                $log->debug("Bad token");

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
                $id =$this->request->params["id"] ?? null;

                $response = isset($id) ? $controller->$action($id) : $controller->$action();

                // ajout du jeton
                $this->setSessionToken();

                // met à jour page précédente
                if (!$this->request->isAjax()) {
                    $query = $this->request->query;
                    $previousPage = $this->request->path . ($query !== "" ? ("?" . $query) : "");
                    $this->setUserPreviousPage($previousPage);
                }

                $response->send();
            } catch (Exception $exception) {
                $log = new Logger();
                $log->debug($exception->getMessage());
                $log = new Logger();
                $log->debug($exception->getTraceAsString());

                $this->triggerError503();
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
            $errorsController->error404Json()->send();
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
            $errorsController->error503Json()->send();
        } else {
            $errorsController->error503()->send();
        }
    }
}