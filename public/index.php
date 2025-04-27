<?php

session_start();

define("ROOT", dirname(__DIR__));
define("VIEWS", ROOT  . str_replace("/", DIRECTORY_SEPARATOR, "/src/Views/"));

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

if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [];
    $_SESSION["user"]["role"] = "guest";
}

$dispatcher = new Dispatcher();
$dispatcher->run();
