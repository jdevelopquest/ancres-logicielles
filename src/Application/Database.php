<?php

namespace App\Application;

use App\Application\Config\DatabasesSettings;
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
     * @param string $request
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public static function fetch(string $request, array $params = []): mixed
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
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
     * @return bool
     * @throws Exception
     */
    private static function isPdoCanBeUsed(): bool
    {
        if (empty(self::$pdo)) {
            try {
                extract(DatabasesSettings::DB_CONNECTION_PARAMS);

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
     * @param string $request
     * @param array $params
     * @return bool
     * @throws Exception
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
     * @param string $request
     * @param array $params
     * @return array
     * @throws Exception
     */
    public static function fetchAll(string $request, array $params = []): array
    {
        try {
            if (!self::isPdoCanBeUsed()) {
                throw new Exception();
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
