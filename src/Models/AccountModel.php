<?php

namespace App\Models;

use App\Application\Config\AppSettings;
use App\Application\Database;
use Exception;

/**
 * AccountModel handles all database operations related to user accounts.
 *
 * This class manages various profile-related operations including:
 * - Account authentication and login
 * - Account status management (banned, suspended, admin, moderator)
 * - Account retrieval and validation
 * - Login attempt tracking and suspension handling
 *
 * The model interacts with the Accounts table in the database and provides
 * methods to handle profile statuses, suspension periods, and administrative operations.
 *
 * @package models
 */
class AccountModel
{
    /**
     * Deletes a profile from the database identified by its profile ID.
     *
     * @param int $idAccount The ID of the profile to delete.
     * @return bool True if the operation was successful, otherwise false.
     * @throws Exception
     */
    public function deleteAccount(int $idAccount): bool
    {
        $request =
            "DELETE FROM Accounts WHERE idAccount = :idAccount";
        $params = [":idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * Retrieves the IDs and usernames of all registered accounts.
     *
     * @return array An array containing all accounts with their IDs and usernames.
     *               Each array element is an associative pair with the following keys:
     *               - idAccount: The unique identifier of the profile.
     *               - accountUsername: The username associated with the profile.
     * @throws Exception
     */
    public function getAllAccounts(): array
    {
        $request =
            "SELECT 
                idAccount, 
                accountUsername,
                accountIsBanned,
                accountIsAdmin,
                accountIsModerator,
                accountIsSuspended
            FROM Accounts";

        return Database::fetchAll($request);
    }

    /**
     * Retrieves the status of a profile based on the provided profile ID.
     * This function fetches details such as whether the profile is banned,
     * an admin, a moderator, or suspended from the database.
     *
     * @param string $idAccount The unique identifier of the profile to retrieve the status for.
     * @return array An associative array containing the profile status details, or an empty array
     *               if the profile is not found. The details include:
     *               accountIsBanned, accountIsAdmin, accountIsModerator, accountIsSuspended.
     * @throws Exception
     */
    public function getAccountStatus(string $idAccount): array
    {
        $request =
            "SELECT 
                Accounts.idAccount,
                accountIsBanned, 
                accountIsAdmin, 
                accountIsModerator,
                accountIsSuspended
              FROM Accounts
              WHERE idAccount = :idAccount";

        $params = [":idAccount" => $idAccount];

        return Database::fetch($request, $params) ?? [];
    }

    /**
     * @throws Exception
     */
    public function loginWithPassword(string $username, string $password): array
    {
        $account = $this->getAccountByUsername($username);

        if (empty($account)) {
            return [];
        }

        if (password_verify($password, $account["accountPassword"])) {

            unset($account["accountPassword"]);

            return $account;
        }

        return [];
    }

    /**
     * Retrieves the profile details for a given username to facilitate login.
     *
     * @param string $username The username associated with the profile to retrieve.
     * @return array An array containing the profile details, or an empty array if the profile cannot be found.
     * @throws Exception
     */
    public function getAccountForLogin(string $username): array
    {
        $idAccount = $this->getAccountIdByUsername($username);

        if (is_null($idAccount)) {
            return [];
        }

        $this->checkSuspendedDurationByIdAccount($idAccount);

        return $this->getAccountByUsername($username);
    }

    /**
     * @param $username
     * @return mixed
     * @throws Exception
     */
    private function getAccountIdByUsername($username): mixed
    {
        $request =
            "SELECT 
                    idAccount
            FROM 
                Accounts 
            WHERE 
                accountUsername = :username";

        $params = ["username" => $username];

        $results = Database::fetch($request, $params);

        return $results["idAccount"] ?? null;
    }

    /**
     * @param int $idAccount
     * @return void
     * @throws Exception
     */
    private function checkSuspendedDurationByIdAccount(int $idAccount): void
    {
        $timestamp = time();

        $request =
            "SELECT accountIsSuspended, suspensionEndTime FROM Accounts WHERE idAccount = :idAccount";

        $params = ["idAccount" => $idAccount];

        $results = Database::fetch($request, $params);

        // si le temps de suspension est écoulé, la suspension est annulée
        // si le compte est suspendu et que la marque de fin de suspension est nulle, la suspension est annulée
        if (
            !empty($results) &&
            (!is_null($results["suspensionEndTime"]) && ($results["suspensionEndTime"] < $timestamp)) ||
            (is_null($results["suspensionEndTime"] && $results["accountIsSuspended"] == 1))
        ) {
            $this->cancelSuspendAccount($idAccount);
        }
    }

    /**
     * @param int $idAccount
     * @return bool
     * @throws Exception
     */
    public function cancelSuspendAccount(int $idAccount): bool
    {
        $request =
            "UPDATE 
                Accounts 
            SET accountIsSuspended = 0, suspensionEndTime = NULL, failedLoginAttempts = :failedLoginAttempts
            WHERE idAccount = :idAccount";

        $params = ["idAccount" => $idAccount, "failedLoginAttempts" => AppSettings::ACCOUNT_MAX_LOGIN_ATTEMPTS];

        return Database::execute($request, $params);
    }

    /**
     * @param string $username
     * @return array
     * @throws Exception
     */
    private function getAccountByUsername(string $username): array
    {
        $request =
            "SELECT 
                idAccount,
                accountUsername,
                accountPassword, 
                accountIsBanned, 
                accountIsAdmin, 
                accountIsModerator,
                accountIsSuspended, 
                failedLoginAttempts, 
                suspensionEndTime
            FROM 
                Accounts 
            WHERE 
                accountUsername = :username";

        $params = ["username" => $username];

        return Database::fetch($request, $params);
    }

    /**
     * @param string $accountUsername
     * @param string $accountPassword
     * @return bool
     * @throws Exception
     */
    public function registerAccount(string $accountUsername, string $accountPassword): bool
    {
        if (!$this->isUsernameMatchPattern($accountUsername)) {
            return false;
        }

        if (!$this->isPasswordMatchPattern($accountPassword)) {
            return false;
        }

        $accountPassword = password_hash($accountPassword, AppSettings::PASSWORD_ALGO);

        $request =
            "INSERT INTO 
                Accounts(
                    accountUsername,
                    accountPassword,
                    accountIsBanned,
                    accountIsAdmin,
                    accountIsModerator,
                    accountIsSuspended,
                    failedLoginAttempts)
            VALUES(
                :accountUsername,
                :accountPassword,
                :accountIsBanned,
                :accountIsAdmin,
                :accountIsModerator,
                :accountIsSuspended,
                :failedLoginAttempts)";

        $params = [
            ":accountUsername" => $accountUsername,
            ":accountPassword" => $accountPassword,
            ":accountIsBanned" => 0,
            ":accountIsAdmin" => 0,
            ":accountIsModerator" => 0,
            ":accountIsSuspended" => 0,
            ":failedLoginAttempts" => AppSettings::ACCOUNT_MAX_LOGIN_ATTEMPTS
        ];

        return Database::execute($request, $params);
    }

    /**
     * @param string $username
     * @return bool
     */
    public function isUsernameMatchPattern(string $username): bool
    {
        return preg_match(AppSettings::USERNAME_PATTERN, $username) === 1;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordMatchPattern(string $password): bool
    {
        // L'utilisation de la constante PASSWORD_BCRYPT pour l'algorithme fera que le paramètre password sera tronqué
        // à une longueur maximale de 72 octets.
        if (mb_strlen($password, "8bit") > AppSettings::PASSWORD_MAX_BYTES) {
            return false;
        }

        return preg_match(AppSettings::PASSWORD_PATTERN, $password) === 1;
    }

    /**
     * @param $username
     * @return bool
     * @throws Exception
     */
    public function isUsernameExist($username): bool
    {
        $result = $this->getAccountIdByUsername($username);

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $idAccount
     * @return void
     * @throws Exception
     */
    public function addFailedLoginAttempts(string $idAccount): void
    {
        $request =
            "UPDATE Accounts SET failedLoginAttempts = failedLoginAttempts - 1 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        Database::execute($request, $params);
        $this->checkFailedLoginAttempts($idAccount);
    }

    /**
     * @param int $idAccount
     * @return void
     * @throws Exception
     */
    private function checkFailedLoginAttempts(int $idAccount): void
    {
        $request =
            "SELECT failedLoginAttempts FROM Accounts WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        $results = Database::fetch($request, $params);
        if (!empty($results) && $results["failedLoginAttempts"] <= 0) {
            $this->suspendAccount($idAccount);
        }
    }

    /**
     * @param int $idAccount
     * @return bool
     * @throws Exception
     */
    public function suspendAccount(int $idAccount): bool
    {
        $timestampFutur = Time() + AppSettings::ACCOUNT_SUSPENSION_DURATION;
        $request =
            "UPDATE 
                Accounts 
            SET accountIsSuspended = 1, suspensionEndTime = :timestampFutur 
            WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount, "timestampFutur" => $timestampFutur];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function unbanAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsBanned = 0 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function banAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsBanned = 1 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function revokeAdminAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsAdmin = 0 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function revokeModeratorAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsModerator = 0 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function grantAdminAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsAdmin = 1 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param $idAccount
     * @return bool
     * @throws Exception
     */
    public function grantModeratorAccount($idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsModerator = 1 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }
}
