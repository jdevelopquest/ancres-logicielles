<?php
declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . "/config/paths.php";

require_once dirname(__DIR__) . "/src/autoload.php";

use App\Controllers\ErrorsController;
use App\Core\Application;
use App\Core\Request;
use App\Core\Response;
use App\Utils\Logger;

//
set_exception_handler(
    function ($exception) {
        $log = new Logger();
        $log->debug("From exception handler function", ["exception message" => $exception->getMessage(), "exception line" => $exception->getLine(), "exception file" => $exception->getFile() ]);
        // todo il faut pouvoir gérer les requête ajax
        $errorsController = new ErrorsController(new Request(), new Response());
        $errorsController->error503()->send();
        exit();
    }
);

set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        $log = new Logger();
        $log->debug("From error handler function", ["errno" => $errno, "errstr" => $errstr, "errfile" => $errfile, "errline" => $errline]);
        // todo il faut pouvoir gérer les requête ajax
        $errorsController = new ErrorsController(new Request(), new Response());
        $errorsController->error503()->send();
        exit();
    }
);

$application = new Application();
$application->run();
