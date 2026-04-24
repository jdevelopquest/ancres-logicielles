<?php

session_start();

define("ROOT", dirname(__DIR__));
define("VIEWS", ROOT . str_replace("/", DIRECTORY_SEPARATOR, "/src/Views/"));
define("LOG", ROOT . str_replace("/", DIRECTORY_SEPARATOR, "/log/"));

spl_autoload_register(
    function ($class) {
        // charge les classes de l'espace App
        $path = str_replace("App\\", "src\\", $class);
        $file = ROOT . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $path) . ".php";
        if (file_exists($file)) {
            require $file;
        }
    }
);

use App\Application\Dispatcher;
use App\Application\Response;

set_exception_handler(
    function ($exception) {
        $file = LOG . "messages.log";
        $message = sprintf("%s on line %s in %s\n", $exception->getMessage(), $exception->getLine(), $exception->getFile());
        error_log($message, 3, $file);
        $response = new Response(true);
        $response->send();
        exit();
    }
);

set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        $file = LOG . "messages.log";
        $message = sprintf("%s %s on line %s in %s\n", $errno, $errstr, $errline, $errfile);
        error_log($message, 3, $file);
        $response = new Response(true);
        $response->send();
        exit();
    }
);

$dispatcher = new Dispatcher();
$dispatcher->run();
