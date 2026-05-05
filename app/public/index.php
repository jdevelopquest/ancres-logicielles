<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "autoload.php";

use App\Application;

$application = new Application();
$application->run();
