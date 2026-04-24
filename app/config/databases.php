<?php
declare(strict_types=1);

return [
    "db_driver" => getenv("DATABASE_DRIVER") ?: "mysql",
    "db_host" => getenv("MARIADB_HOST") ?: "database",
    "db_name" => getenv("MARIADB_DATABASE") ?: "ancres-logicielles",
    "db_username" => getenv("MARIADB_USER") ?: "al",
    "db_password" => getenv("MARIADB_PASSWORD") ?: "al-password",
];
