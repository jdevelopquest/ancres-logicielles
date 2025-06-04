<?php

namespace App\Application\Utils;

use Exception;

trait SessionManager
{
    // todo : faire un logout en base de données
    // todo : supprimer les appels aux instances response et request
    /**
     * Initializes the session by setting default values for the "user" session data
     * if it is not already defined. This includes the user's role, ID, and theme
     * preferences based on the available cookies or default values.
     *
     * @return void
     */
    protected function initSession(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["user"] = [];
            $_SESSION["user"]["role"] = "guest";
            $_SESSION["user"]["id"] = "none";
            $_SESSION["user"]["theme"] = $this->request->getCookies()["theme"] ?? "theme-light";
            $_SESSION["user"]["previousPage"] = $this->request->getCookies()["previousPage"] ?? "";
        }
    }

    /**
     * Configures the user session by populating session data with the provided account details.
     * Initializes the session if it has not been set up already. Assigns a user ID, username,
     * and determines the user's role based on the account's attributes such as moderator or admin status.
     *
     * @param array $account An associative array containing account details, including:
     *                       - idAccount: The unique identifier of the account.
     *                       - accountUsername: The username associated with the account.
     *                       - accountIsModerator: A boolean indicating if the user is a moderator.
     *                       - accountIsAdmin: A boolean indicating if the user is an admin.
     *
     * @return void
     */
    protected function setupUserSession(array $account): void
    {
        if (!isset($_SESSION["user"])) {
            $this->initSession();
        }

        $_SESSION["user"]["id"] = $account["idAccount"];
        $_SESSION["user"]["username"] = $account["accountUsername"];
        $_SESSION["user"]["role"] = "registered";

        if ($account["accountIsModerator"]) {
            $_SESSION["user"]["role"] = "moderator";
        }

        if ($account["accountIsAdmin"]) {
            $_SESSION["user"]["role"] = "admin";
        }
    }

    /**
     * Retrieves the role of the currently authenticated user from the session data.
     * If the role is not set, it defaults to "guest".
     *
     * @return string The user's role or "guest" if no role is found in the session.
     */
    protected function getUserRole(): string
    {
        return $_SESSION["user"]["role"] ?? "guest";
    }

    /**
     * Retrieves the user's theme preference from the session.
     *
     * @return string The user's theme preference, or "theme-light" if no preference is set.
     */
    protected function getUserTheme(): string
    {
        return $_SESSION["user"]["theme"] ?? "theme-light";
    }

    /**
     * Sets the user's theme preference by adding a cookie with the specified theme value.
     * The cookie is set to expire after 30 days.
     *
     * @param string $theme The theme identifier to be stored in the cookie.
     * @return void
     */
    protected function setUserTheme(string $theme): void
    {
        // todo : vérifier que le thème est bien valide
        $_SESSION["user"]["theme"] = $theme;
        $this->response->addCookie(
            "theme",
            $theme,
            time() + (86400 * 30),  // 30 jours
        );
    }

    /**
     * Retrieves the user ID from the session.
     *
     * @return string The user ID from the session, or "none" if not set.
     */
    protected function getUserId(): string
    {
        return $_SESSION["user"]["id"] ?? "none";
    }

    /**
     * Checks if the user is logged in by verifying the session data.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    protected function userIsLoggedIn(): bool
    {
        return $_SESSION["user"]["id"] !== "none";
    }

    /**
     * Determines if the current user has a "guest" role.
     *
     * @return bool True if the user role is "guest", otherwise false.
     */
    protected function userIsGuest(): bool
    {
        return $_SESSION["user"]["role"] === "guest";
    }

    /**
     * Checks if the user is registered based on their role in the session.
     *
     * @return bool True if the user's role in the session is "registered", otherwise false.
     */
    protected function userIsRegistered(): bool
    {
        return $_SESSION["user"]["role"] === "registered";
    }

    /**
     * Determines if the current user has the "moderator" role.
     *
     * @return bool True if the user's role is "moderator", false otherwise.
     */
    protected function userIsModerator(): bool
    {
        return $_SESSION["user"]["role"] === "moderator";
    }

    /**
     * Determines if the current user has an admin role.
     *
     * @return bool True if the user's role is "admin", false otherwise.
     */
    protected function userIsAdmin(): bool
    {
        return $_SESSION["user"]["role"] === "admin";
    }

    /**
     * Logs out the current user by clearing the session and destroying it.
     * Performs session-related cleanup tasks such as unsetting user data and destroying the session.
     *
     * @return void
     */
    protected function userLogout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {

            // todo : faire un logout en base de données
            // todo : traiter les cookies de la session

            unset($_SESSION["user"]);

            session_destroy();

            $this->initSession();
        }
    }

    /**
     * Sets the user's previous page in the session.
     *
     * @param string $previousPage The URL or identifier of the previous page to be stored.
     * @return void
     */
    protected function setUserPreviousPage(string $previousPage): void
    {
        $_SESSION["user"]["previousPage"] = $previousPage;
    }

    /**
     * Retrieves the previous page visited by the user from the session.
     *
     * @return string The URL of the previous page visited by the user, or an empty string if not set.
     */
    protected function getUserPreviousPage(): string
    {
        return $_SESSION["user"]["previousPage"] ?? "";
    }

    /**
     * Validates if the session token matches the token in the request cookies.
     *
     * @return bool True if the tokens match, false otherwise.
     */
    protected function isValidToken(): bool
    {
        if (!isset($_SESSION["token"]) || !isset($this->request->getCookies()["token"])) {
            $this->logMessage("Pas de token dans la session ou dans les cookies");
            return false;
        }

        return $_SESSION["token"] === $this->request->getCookies()["token"];
    }

    /**
     * Initializes a session token and sets it as a cookie if generated successfully.
     *
     * @return void
     */
    protected function setSessionToken(): void
    {
        $_SESSION["token"] = $this->generateToken();
        if (empty($_SESSION["token"])) {
            $this->logMessage("Impossible de générer un jeton de session");
        } else {
            $this->logMessage("Jeton de session généré");
            $this->response->addCookie(
                'token',
                $_SESSION["token"],
                time() + 3600, // 1 heure
                "/",
                "",
                true,
                false
            );
        }
    }

    /**
     * Generates a secure random token.
     *
     * @return string The generated token as a hexadecimal string, or an empty string if token generation fails.
     */
    private function generateToken(): string
    {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            // todo prévoir une action si la génération du jeton échoue
            return "";
        }
    }
}