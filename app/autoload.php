<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    // charge les classes de l'espace App
    $path = str_replace("App\\", "src\\", $class);
    $file =
        __DIR__ .
        DIRECTORY_SEPARATOR .
        str_replace("\\", DIRECTORY_SEPARATOR, $path) .
        ".php";
    if (file_exists($file)) {
        require $file;
    }
});
