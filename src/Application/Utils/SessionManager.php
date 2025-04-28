<?php

namespace App\Application\Utils;

trait SessionManager
{
    public function initSession(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["user"] = [];
            $_SESSION["user"]["role"] = "guest";
            $_SESSION["user"]["theme"] = "theme-light";
            $_SESSION["user"]["id"] = "none";
        }
    }

    public function getUserRole(): string
    {
        return $_SESSION["user"]["role"] ?? "guest";
    }

    public function getUserTheme(): string
    {
        return $_SESSION["user"]["theme"] ?? "theme-light";
    }

    public function setUserTheme(string $theme):void
    {
        $_SESSION["user"]["theme"] = $theme;
    }

    public function getUserId(): string
    {
        return $_SESSION["user"]["id"] ?? "none";
    }

    public function userIsLoggedIn(): bool
    {
        return $_SESSION["user"]["id"] !== "none";
    }

    public function userIsGuest(): bool
    {
        return $_SESSION["user"]["role"] === "guest";
    }

    public function userIsRegistered(): bool
    {
        return $_SESSION["user"]["role"] === "registered";
    }

    public function userIsModerator(): bool
    {
        return $_SESSION["user"]["role"] === "moderator";
    }

    public function userIsAdmin(): bool
    {
        return $_SESSION["user"]["role"] === "admin";
    }

    public function userLogout(): void
    {
        unset($_SESSION["user"]);
    }
}