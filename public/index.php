<?php

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
use App\Application\Router;


function pretty_urls() :string {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);var_dump($path);
    $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);var_dump($params);
    $path_pattern = '#/ancres_logicielles/public/index\.php$#';
    $params_pattern = '#ctr=(\w+)&act=(\w+)$#';
    if (!preg_match($path_pattern, $path)) {
        exit("404 Not Found");
    }
    $matches = [];
    if (!preg_match($params_pattern, $params, $matches)) {
        exit("404 Not Found");
    }
    var_dump($matches);
    return '/' . $matches[1] . '/' . $matches[2];
}
//
//$router = new Router();
//$router->add('/', ['controller' => 'HomeController', 'action' => 'index']);
//$router->add('/articles/index', ['controller' => 'ArticlesController', 'action' => 'index']);
//
//$dispatcher = new Dispatcher($router);
//$dispatcher->handle(pretty_urls());

//$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//echo "<p>$path</p>";
//
//$params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
//echo "<p>$params</p>";
//
//$method = $_SERVER['REQUEST_METHOD'];
//echo "<p>$method</p>";

ob_start();
include_once ROOT . str_replace('/', DIRECTORY_SEPARATOR, '/src/Views/articles/index') . '.php';
$content = ob_get_clean();
ob_start();
include_once ROOT . str_replace('/', DIRECTORY_SEPARATOR, '/src/Views/layouts/main') . '.php';
$main = ob_get_clean();
$page = str_replace('{{content}}', $content, $main);
echo $page;