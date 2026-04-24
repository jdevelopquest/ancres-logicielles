<?php
declare(strict_types=1);

define("ROOT_PATH", dirname(__DIR__));
define("VIEWS_PATH", ROOT_PATH . str_replace("/", DIRECTORY_SEPARATOR, "/src/Views/"));
define("CONFIG_PATH", ROOT_PATH . str_replace("/", DIRECTORY_SEPARATOR, "/config/"));
define("VAR_PATH", ROOT_PATH . str_replace("/", DIRECTORY_SEPARATOR, "/var/"));
define("LOG_PATH", VAR_PATH . str_replace("/", DIRECTORY_SEPARATOR, "/log/"));