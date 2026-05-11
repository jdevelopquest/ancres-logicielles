<?php
declare(strict_types=1);

namespace App\CoreUtils\Database;

readonly class DatabaseMariaDB
{
    /*
     * @param string driver
     * @param string $host
     * @param int $port
     * @param string $name
     * @param string $username
     * @param string $password
     */
    public function __construct(
        public string $driver,
        public string $host,
        public int $port,
        public string $name,
        public string $username,
        #[\SensitiveParameter] public string $password,
    ) {}
}
