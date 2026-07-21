<?php
declare(strict_types=1);

use App\CoreUtils\Database\DatabaseMariaDB;

return new DatabaseMariaDB(
    driver: getenv("MARIADB_DRIVER") ?: "mysql",
    host: getenv("MARIADB_HOST") ?: "database",
    port: getenv("MARIADB_PORT") ?: "3306",
    name: getenv("MARIADB_DATABASE") ?: "ancres-logicielles",
    password: getenv("MARIADB_PASSWORD") ?: "al-password",
    username: getenv("MARIADB_USER") ?: "al",
);
