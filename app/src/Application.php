<?php
declare(strict_types=1);

namespace App\Src;

session_start();

require_once dirname(__DIR__) . "/config/databases.php";
require_once dirname(__DIR__) . "/config/paths.php";
require_once dirname(__DIR__) . "/config/routes.php";

use App\Core\Configure;
use App\Core\Dispatcher;
use App\Core\Request;
use App\Core\Response;
use App\CoreUtils\Logger\Logger;
use App\CoreUtils\Session\SessionManager;
use App\Src\Controllers\ErrorsController;
use Exception;

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

    /**
     * @throws Exception
     */
    public function __construct()
    {
        //
        set_exception_handler(function ($exception) {
            $log = new Logger();
            $log->debug("From exception handler function", [
                "exception message" => $exception->getMessage(),
                "exception line" => $exception->getLine(),
                "exception file" => $exception->getFile(),
            ]);
            // todo il faut pouvoir gérer les requête ajax
            $errorsController = new ErrorsController(
                new Request(),
                new Response(),
            );
            $errorsController->error503()->send();
            exit();
        });

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $log = new Logger();
            $log->debug("From error handler function", [
                "errno" => $errno,
                "errstr" => $errstr,
                "errfile" => $errfile,
                "errline" => $errline,
            ]);
            // todo il faut pouvoir gérer les requête ajax
            $errorsController = new ErrorsController(
                new Request(),
                new Response(),
            );
            $errorsController->error503()->send();
            exit();
        });

        $this->request = new Request();
        $this->response = new Response();
        $this->initSession();

        $configurations = require CONFIG_PATH . "app.php";
        if (is_array($configurations)) {
            Configure::merge($configurations);
        } else {
            throw new Exception("Config file is not an array");
        }
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
