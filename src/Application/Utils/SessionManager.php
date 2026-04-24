<?php

namespace App\Application\Utils;

trait SessionManager
{
    public function initSession(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["user"] = [];
            $_SESSION["user"]["role"] = "guest";
            $_SESSION["user"]["id"] = "none";
        }
    }

    public function getUserRole(): string
    {
        return $_SESSION["user"]["role"] ?? "guest";
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

    public function userIsAdmin(): bool
    {
        return $_SESSION["user"]["role"] === "admin";
    }

    public function logout(): void
    {
        unset($_SESSION["user"]);
    }
}