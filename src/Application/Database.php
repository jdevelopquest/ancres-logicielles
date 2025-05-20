<?php

namespace App\Application;

use Exception;
use PDO;
use Throwable;

/**
 *
 */
class Database
{
    private static PDO $pdo;

    /**
     * Fetches a single record from the database based on the provided SQL request and parameters.
     *
     * @param string $request The SQL query to execute.
     * @param array $params An associative array of key-value pairs to bind to the query.
     * @return mixed The fetched result or an empty array if no results are found.
     * @throws Exception
     */
    public static function fetch(string $request, array $params = []): mixed
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception("Unable to connect to the database.");
            }

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
     * Determines if the PDO connection can be successfully established and used.
     *
     * @return bool
     * @throws Exception
     */
    private static function isPdoCanBeUsed(): bool
    {
        if (!isset(self::$pdo)) {
            try {
                $connectionParams = [];
                // le fichier databases.php doit returner un tableau associatif
                // avec les clefs db_driver, db_host, db_name, db_username et db_password
                if (file_exists(CONFIG . "databases.php")) {
                    $connectionParams = require CONFIG . "databases.php";
                } else {
                    throw new Exception("Missing database configuration file.");
                }

                extract($connectionParams);

                self::$pdo = new PDO(
                    $db_driver . ":host=" . $db_host . ";dbname=" . $db_name,
                    $db_username,
                    $db_password,
                    array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                );
            } catch (Throwable $t) {
                throw new Exception($t->getMessage());
            }
        }

        return true;
    }

    /**
     * Executes a prepared SQL statement with the given parameters.
     *
     * @param string $request The SQL query to be executed.
     * @param array $params An associative array of parameters to bind to the query.
     * @return bool Returns true if the statement was executed successfully, otherwise false.
     * @throws Exception If there is an issue with the PDO connection or query execution.
     */
    public static function execute(string $request, array $params = []): bool
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
            }

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
    public static function fetchAll(string $request, array $params = []): array
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception("Unable to connect to the database.");
            }

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
    public static function beginTransaction(): bool
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
            }
            return self::$pdo->beginTransaction();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public static function commit(): bool
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
            }
            return self::$pdo->commit();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public static function rollback(): bool
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
            }
            return self::$pdo->rollBack();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @return false|string
     * @throws Exception
     */
    public static function getLastInsertId(): false|string
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
            }
            return self::$pdo->lastInsertId();
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }
}
