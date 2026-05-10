<?php
use App\CoreUtils\Database\DatabaseMariaDB;
define(
    constant_name: "DATABASE",
    value: new DatabaseMariaDB(
        driver: getenv("DATABASE_DRIVER") ?? "mysql",
        host: getenv("DATABASE_HOST") ?? "database",
        port: getenv("DATABASE_PORT") ?? "3306",
        name: getenv("DATABASE_DATABASE") ?? "ancres-logicielles",
        password: getenv("DATABASE_PASSWORD") ?? "al-password",
        username: getenv("DATABASE_USER") ?? "al",
    ),
);
