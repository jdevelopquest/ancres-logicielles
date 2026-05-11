<?php
declare(strict_types=1);

namespace App\CoreUtils\Database;

use Exception;
use PDO;
use PDOException;

final class Connection
{
    private ?PDO $pdo = null;

    public function __construct(private DatabaseMariaDB $databaseMariaDB) {}

    public function getPDO(): PDO
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    $this->databaseMariaDB->driver .
                        ":host=" .
                        $this->databaseMariaDB->host .
                        ";port=" .
                        $this->databaseMariaDB->port .
                        ";dbname=" .
                        $this->databaseMariaDB->name .
                        ";charset=utf8mb4",
                    $this->databaseMariaDB->username,
                    $this->databaseMariaDB->password,
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
