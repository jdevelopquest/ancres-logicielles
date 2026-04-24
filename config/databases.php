<?php
declare(strict_types=1);

return [
    'db_driver'   => getenv('DB_DRIVER') ?: 'mysql',
    'db_host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'db_name'     => getenv('DB_NAME') ?: 'ancres_logicielles',
    'db_username' => getenv('DB_USERNAME') ?: 'root',
    'db_password' => getenv('DB_PASSWORD') ?: '',
];
