<?php

session_start();

define('ROOT', dirname(__DIR__));

spl_autoload_register(
    function ($class) {
        // charge les classes de l'espace App
        $path = str_replace('App\\', 'src\\', $class);
        $file = ROOT . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $path) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
);

use App\Application\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->run();

//ob_start();
//include_once ROOT . str_replace('/', DIRECTORY_SEPARATOR, '/src/Views/articles/index') . '.php';
//$content = ob_get_clean();
//ob_start();
//include_once ROOT . str_replace('/', DIRECTORY_SEPARATOR, '/src/Views/layouts/main') . '.php';
//$main = ob_get_clean();
//$page = str_replace('{{content}}', $content, $main);
//echo $page;