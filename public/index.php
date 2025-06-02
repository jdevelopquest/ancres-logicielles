<?php

session_start();

define("ROOT_PATH", dirname(__DIR__));
require_once ROOT_PATH . "/src/Application/Config/paths.php";

spl_autoload_register(
    function ($class) {
        // charge les classes de l'espace App
        $path = str_replace("App\\", "src\\", $class);
        $file = ROOT_PATH . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $path) . ".php";
        if (file_exists($file)) {
            require $file;
        }
    }
);

use App\Application\Application;
use App\Application\Request;
use App\Application\Response;
use App\Controllers\ErrorsController;

//
set_exception_handler(
    function ($exception) {
        $file = LOG_PATH . "messages.log";
        $message = sprintf("%s on line %s in %s\n", $exception->getMessage(), $exception->getLine(), $exception->getFile());
        error_log($message, 3, $file);
        // todo il faut pouvoir gérer les requête ajax
        $errorsController = new ErrorsController(new Request(), new Response());
        $errorsController->error503()->send();
        exit();
    }
);

set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        $file = LOG_PATH . "messages.log";
        $message = sprintf("%s %s on line %s in %s\n", $errno, $errstr, $errline, $errfile);
        error_log($message, 3, $file);
        // todo il faut pouvoir gérer les requête ajax
        $errorsController = new ErrorsController(new Request(), new Response());
        $errorsController->error503()->send();
        exit();
    }
);

$application = new Application();
$application->run();
