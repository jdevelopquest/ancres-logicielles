<?php
declare(strict_types=1);

namespace App\Core;

use Exception;
use PDO;
use Throwable;

/**
 *
 */
final class DatabaseHandler
{
    private static PDO $pdo;

    /**
     * @throws Exception
     */
    //TODO: argument de type DATABASE_URL
    public function __construct()
    {
        if (!isset(self::$pdo)) {
            try {
                // $connectionParams = [];
                // // le fichier databases.php doit returner un tableau associatif
                // // avec les clefs db_driver, db_host, db_name, db_username et db_password
                // if (file_exists(CONFIG_PATH . "databases.php")) {
                //     $connectionParams = require CONFIG_PATH . "databases.php";
                // } else {
                //     throw new Exception("Missing database configuration file.");
                // }

                // extract($connectionParams);
                $driver = getenv("DATABASE_DRIVER");
                $host = getenv("DATABASE_HOST");
                $dbname = getenv("DATABASE_DATABASE");
                $dsn = "$driver:dbname=$dbname;host=$host";
                $user = getenv("DATABASE_USER");
                $password = getenv("DATABASE_PASSWORD");

                self::$pdo = new PDO($dsn, $user, $password, [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (Throwable $t) {
                throw new Exception($t->getMessage());
            }
        }
    }

    /**
     * Fetches a single record from the database based on the provided SQL request and parameters.
     *
     * @param string $request The SQL query to execute.
     * @param array $params An associative array of key-value pairs to bind to the query.
     * @return mixed The fetched result or false if no results are found.
     * @throws Exception
     */
    public function fetch(string $request, array $params = []): mixed
    {
        try {
            $query = self::$pdo->prepare($request);

            foreach ($params as $key => $value) {
                $query->bindValue("$key", $value);
            }

            if (!$query->execute()) {
                $query->closeCursor();
                return [];
            }

            $result = $query->fetch();

            $query->closeCursor();

            return $result;
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * Executes a prepared SQL statement with the given parameters.
     *
     * @param string $request The SQL query to be executed.
     * @param array $params An associative array of parameters to bind to the query.
     * @return bool Returns true if the statement was executed successfully, otherwise false.
     * @throws Exception If there is an issue with the PDO connection or query execution.
     */
    public function execute(string $request, array $params = []): bool
    {
        try {
            $query = self::$pdo->prepare($request);

            foreach ($params as $key => $value) {
                $query->bindValue("$key", $value);
            }

            $result = $query->execute();

            $query->closeCursor();

            return $result;
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * Executes a database query and fetches all results.
     *
     * @param string $request The SQL query string to be executed.
     * @param array $params An optional associative array of parameters to bind to the query.
     * @return array An array containing all fetched results. Returns an empty array if the query fails or no results are found.
     * @throws Exception If the PDO instance is unavailable, or if an error occurs during query execution.
     */
    public function fetchAll(string $request, array $params = []): array
    {
        try {
            $query = self::$pdo->prepare($request);

            foreach ($params as $key => $value) {
                $query->bindValue("$key", $value);
            }

            if (!$query->execute()) {
                $query->closeCursor();
                return [];
            }

            $result = $query->fetchAll();

            $query->closeCursor();

            return $result;
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function beginTransaction(): bool
    {
        try {
            return self::$pdo->beginTransaction();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function commit(): bool
    {
        try {
            return self::$pdo->commit();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function rollback(): bool
    {
        try {
            return self::$pdo->rollBack();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return false|string
     * @throws Exception
     */
    public function getLastInsertId(): false|string
    {
        try {
            return self::$pdo->lastInsertId();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }
}
