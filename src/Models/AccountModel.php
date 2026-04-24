<?php

namespace App\Models;

use App\Application\Config\AppSettings;
use App\Application\Database;
use Exception;

/**
 * Represents the model for managing accounts within the application.
 * Provides methods for handling account-related functionality such as
 * deletion, retrieval, authentication, registration, and validation.
 */
class AccountModel
{
    /**
     * Deletes an account from the database based on the provided account ID.
     *
     * @param int $idAccount The ID of the account to be deleted.
     * @return bool True on success, false on failure.
     * @throws Exception If there is an issue executing the database operation.
     */
    public function deleteAccount(int $idAccount): bool
    {
        $request =
            "DELETE FROM Accounts WHERE idAccount = :idAccount";
        $params = [":idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * Retrieves all accounts from the database.
     *
     * @return array An array of accounts, each containing keys such as idAccount, accountUsername, accountIsBanned, accountIsAdmin, accountIsModerator, and accountIsSuspended.
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
     * @param string $idAccount The account ID for which the status is being retrieved.
     * @return array The account status details including flags for ban, admin, moderator, and suspension.
     * @throws Exception If the database query fails.
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
     * Authenticates a user using their username and password.
     *
     * @param string $username The username of the account.
     * @param string $password The password of the account.
     * @return array Returns an array of account details if authentication is successful. Returns an empty array if authentication fails.
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
     * Retrieves account details for a given username if the account exists and is not suspended.
     *
     * @param string $username The username used to identify the account.
     * @return array Returns an associative array containing account details. Returns an empty array if the account does not exist.
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
     * @param string $username The username of the account to look up.
     * @return mixed The account ID if found, or null if no matching account exists.
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
     * Checks and updates the suspension status of an account based on its suspension end time.
     * If the suspension period has elapsed or the account suspension end time is not set while the account is marked as suspended,
     * the suspension status will be canceled.
     *
     * @param int $idAccount The unique identifier of the account to be checked.
     * @return void
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
     * Cancels the suspension of an account by updating its suspension status and resetting relevant fields.
     * The account's suspension flag is removed, the suspension end time is cleared,
     * and the failed login attempts counter is reset to the maximum allowed value.
     *
     * @param int $idAccount The unique identifier of the account whose suspension is to be canceled.
     * @return bool Returns true if the operation was successful, otherwise false.
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
     * Retrieves account information from the database based on the provided username.
     *
     * @param string $username The username of the account to be retrieved.
     * @return array An associative array containing account details such as ID, username, password, ban status,
     *               administrative and moderator roles, suspension status, failed login attempts, and suspension end time.
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
     * Registers a new account in the system with the specified username and password.
     * Validates the username and password patterns before attempting registration,
     * hashes the password, and inserts account details into the database.
     *
     * @param string $accountUsername The username for the new account.
     * @param string $accountPassword The plain-text password for the new account.
     * @return bool True if the account was successfully registered; false otherwise.
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
     * Validates whether the given username matches the predefined username pattern.
     *
     * @param string $username The username to be checked against the predefined pattern.
     * @return bool True if the username matches the pattern, false otherwise.
     */
    public function isUsernameMatchPattern(string $username): bool
    {
        return preg_match(AppSettings::USERNAME_PATTERN, $username) === 1;
    }

    /**
     * Validates if the given password matches the defined pattern and adheres to the maximum allowed byte length.
     *
     * @param string $password The password to be validated.
     * @return bool Returns true if the password matches the pattern and is within the valid byte length; false otherwise.
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
     * Checks if a username exists in the system by verifying its associated account ID.
     *
     * @param string $username The username to check for existence.
     * @return bool Returns true if the username exists, otherwise false.
     * @throws Exception
     */
    public function isUsernameExists(string $username): bool
    {
        $result = $this->getAccountIdByUsername($username);

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    /**
     * Decreases the failed login attempts count for a specified account by one
     * and triggers a check on the updated failed login attempts count.
     *
     * @param string $idAccount The unique identifier of the account whose failed login attempts will be updated.
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
     * Checks the number of failed login attempts for a specified account.
     * If the number of failed login attempts is less than or equal to zero,
     * the account will be suspended.
     *
     * @param int $idAccount The unique identifier of the account to be checked.
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
     * Suspends an account by setting its suspension status and end time.
     * The account will remain suspended until the specified suspension duration elapses.
     *
     * @param int $idAccount The unique identifier of the account to be suspended.
     * @return bool True if the suspension operation is successful, false otherwise.
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
     * Removes a ban from the specified account, updating its status in the database.
     *
     * @param int $idAccount The unique identifier of the account to be unbanned.
     * @return bool Returns true if the unban operation was successful, otherwise false.
     * @throws Exception
     */
    public function unbanAccount(int $idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsBanned = 0 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * Revokes administrative privileges of an account by updating its admin status in the database.
     *
     * @param int $idAccount The unique identifier of the account whose admin privileges are to be revoked.
     * @return bool Returns true if the operation is successful, false otherwise.
     */
    public function revokeAdminAccount(int $idAccount): bool
    {
        $request =
            "UPDATE Accounts SET accountIsAdmin = 0 WHERE idAccount = :idAccount";
        $params = ["idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * Revokes moderator privileges for the specified account by updating the account's status in the database.
     *
     * @param int $idAccount The unique identifier of the account whose moderator privileges are to be revoked.
     * @return bool Returns true if the database operation was successful, false otherwise.
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
     * Grants administrative privileges to an account by updating its status in the database.
     *
     * @param mixed $idAccount The unique identifier of the account to be granted administrative privileges.
     * @return bool Returns true if the action was successfully executed, or false otherwise.
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
     * Grants moderator privileges to an account by updating its status in the database.
     *
     * @param int $idAccount The unique identifier of the account to be granted moderator privileges.
     * @return bool Returns true if the operation succeeds, false otherwise.
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
