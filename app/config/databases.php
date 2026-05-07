<?php
use App\Core\DatabaseMariaDB;
define(
    constant_name: "DATABASE_MARIADB",
    value: new DatabaseMariaDB(
        driver: getenv("DATABASE_DRIVER") ?? "mysql",
        host: getenv("DATABASE_HOST") ?? "database",
        name: getenv("DATABASE_DATABASE") ?? "ancres-logicielles",
        password: getenv("DATABASE_PASSWORD") ?? "al-password",
        username: getenv("DATABASE_USER") ?? "al",
    ),
);
