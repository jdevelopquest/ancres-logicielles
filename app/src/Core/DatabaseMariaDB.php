<?php
namespace App\Core;

use Exception;
use PDO;
use PDOException;

final class DatabaseMariaDB
{
    private ?PDO $pdo = null;
    /*
     * @param string driver
     * @param string $host
     * @param string $port
     * @param string $name
     * @param string $username
     * @param string $password
     */
    public function __construct(
        private string $driver,
        private string $host,
        private string $port,
        private string $name,
        private string $username,
        private string $password,
    ) {}

    public function getPDO(): PDO
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    $this->driver .
                        ":dbname=" .
                        $this->name .
                        ";host=" .
                        $this->host .
                        ";port=" .
                        $this->port .
                        ";charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false, // Force les vraies requêtes préparées
                    ],
                );
            } catch (PDOException $e) {
                throw new Exception(
                    "PDO Exception : " . $e->getMessage(),
                    0,
                    $e,
                );
            }
        }
        return $this->pdo;
    }
}
