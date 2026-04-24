<?php

namespace App\Application\Utils;

trait ConstructMenu
{
    use SessionManager;

    protected function constructHref(string $controller, string $action, ?string $id = null): string
    {
        return "index.php?ctr=$controller&act=$action" . (!is_null($id) ? "&id=$id" : "");
    }

    protected function constructMenuHamburger(): array
    {
        $menuHamburger = [];

        $submenu = [];
        $submenu[] = $this->addMenuItem($this->constructHref("articles", "index"), "Accueil", "Accueil", "go-home");
        $menuHamburger[] = $submenu;

        $submenu = [];
        if ($this->userIsLoggedIn()) {
            $submenu[] = $this->addMenuItem($this->constructHref("accounts", "show", $this->getUserId()), "Profil", "Profil", "go-profile");
            $submenu[] = $this->addMenuItem($this->constructHref("accounts", "logout"), "Déconnexion", "Déconnexion", "go-logout");
        } else {
            $submenu[] = $this->addMenuItem($this->constructHref("accounts", "login"), "Connexion", "Connexion", "go-login");
            $submenu[] = $this->addMenuItem($this->constructHref("accounts", "signup"), "Inscription", "Inscription", "go-signup");
        }
        $menuHamburger[] = $submenu;

        if ($this->userIsAdmin()) {
            $submenu = [];
            $submenu[] = $this->addMenuItem($this->constructHref("admins", "index"), "Administration", "Administration", "go-admin");
            $menuHamburger[] = $submenu;
        }

        return ["menu" => $menuHamburger];
    }

    protected function constructMenuTiny(): array
    {
        return $this->constructMenuHamburger();
    }

    protected function addMenuItem(string $href = "", string $title = "", string $text = "", string $icon = ""): array
    {
        return [
            "href" => $href,
            "title" => $title,
            "text" => $text,
            "icon" => $icon
        ];
    }
}