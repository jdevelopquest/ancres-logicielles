<?php
namespace App\Core;

use Exception;
use PDO;
use Throwable;

readonly class DatabaseMariaDB
{
    private PDO $pdo;
    /*
     * @param string driver
     * @param string $host
     * @param string $name
     * @param string $username
     * @param string $password
     */
    public function __construct(
        public string $driver,
        public string $host,
        public string $name,
        public string $username,
        public string $password,
    ) {
        try {
            $this->pdo = new PDO(
                $this->driver .
                    ":dbname=" .
                    $this->name .
                    ";host=" .
                    $this->host,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ],
            );
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
