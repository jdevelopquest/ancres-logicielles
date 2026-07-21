<?php
declare(strict_types=1);

use App\CoreUtils\Database\DatabaseMariaDB;

return new DatabaseMariaDB(
    driver: getenv("MARIADB_DRIVER"),
    host: getenv("MARIADB_HOST"),
    port: getenv("MARIADB_PORT"),
    name: getenv("MARIADB_DATABASE"),
    password: getenv("MARIADB_PASSWORD"),
    username: getenv("MARIADB_USER"),
);
