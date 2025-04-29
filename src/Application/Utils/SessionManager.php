<?php

namespace App\Application\Utils;

trait SessionManager
{
    protected function initSession(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["user"] = [];
            $_SESSION["user"]["role"] = "guest";
            $_SESSION["user"]["id"] = "none";
        }
    }

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

    protected function getUserRole(): string
    {
        return $_SESSION["user"]["role"] ?? "guest";
    }

    protected function getUserTheme(): string
    {
        // Récupérer le choix du thème
        if (isset($this->request->getCookies()["theme"])) {
            return $this->request->getCookies()["theme"];
        }

        return "theme-light";
    }

    protected function setUserTheme(string $theme): void
    {
        setcookie("theme", $theme, time() + (86400 * 30), "/"); // 30 jours
    }

    protected function getUserId(): string
    {
        return $_SESSION["user"]["id"] ?? "none";
    }

    protected function userIsLoggedIn(): bool
    {
        return $_SESSION["user"]["id"] !== "none";
    }

    protected function userIsGuest(): bool
    {
        return $_SESSION["user"]["role"] === "guest";
    }

    protected function userIsRegistered(): bool
    {
        return $_SESSION["user"]["role"] === "registered";
    }

    protected function userIsModerator(): bool
    {
        return $_SESSION["user"]["role"] === "moderator";
    }

    protected function userIsAdmin(): bool
    {
        return $_SESSION["user"]["role"] === "admin";
    }

    protected function userLogout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {

            unset($_SESSION["user"]);

            session_destroy();

            $this->initSession();
        }
    }

    protected function setUserPreviousPage(string $previousPage): void
    {
        setcookie("previousPage", $previousPage, time() + (86400 * 30), "/"); // 30 jours
    }

    protected function getUserPreviousPage(): string
    {
        if (isset($this->request->getCookies()["previousPage"])) {
            return $this->request->getCookies()["previousPage"];
        }

        return "";
    }
}