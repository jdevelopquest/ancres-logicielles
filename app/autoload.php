<?php
spl_autoload_register(function ($class) {
    $path = str_replace(
        search: ["App\\", "Core", "Utils", "Src", "\\"],
        replace: [__DIR__ . "\\", "core", "-utils", "src", DIRECTORY_SEPARATOR],
        subject: $class,
    );
    $file = $path . ".php";
    if (file_exists($file)) {
        require $file;
    }
});
