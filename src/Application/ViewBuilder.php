<?php

namespace App\Application;

use App\Application\Utils\ConstructMenu;

class ViewBuilder
{
    use ConstructMenu;
    protected array $vars = [];
    protected array $files = [];
    protected array $parts = [];

    public function __construct()
    {
        $this->vars["page"] = [];

        $this->files["page"] = $this->constructFilePath("layouts/page");
        $this->files["menu-hamburger"] = $this->constructFilePath("layouts/menu-hamburger");
        $this->files["menu-tiny"] = $this->constructFilePath("layouts/menu-tiny");

        $this->parts["content"] = "";
        $this->parts["menu-hamburger"] = "";
        $this->parts["menu-tiny"] = "";
        $this->parts["page"] = "";
    }

    protected function constructFilePath(string $layout): string
    {
        return VIEWS . str_replace("/", DIRECTORY_SEPARATOR, $layout . ".php");
    }

    public function addTitle(string $title): void
    {
        $this->vars["page"]["title"] = $title;
    }

    public function set(string $key, mixed $value): void
    {
        $this->vars[$key] = $value;
    }

    public function renderTextHTML(): string
    {
        $this->set("menu-hamburger", $this->constructMenuHamburger());
        $this->set("menu-tiny", $this->constructMenuTiny());

        if (file_exists($this->files["page"])) {
            extract($this->vars["page"]);

            ob_start();

            include_once $this->files["page"];

            $this->parts["page"] = ob_get_clean();
        }

        $page = $this->parts["page"];

        if (file_exists($this->files["menu-hamburger"])) {
            if (isset($this->vars["menu-hamburger"])) {
                extract($this->vars["menu-hamburger"]);
            }

            ob_start();

            include_once $this->files["menu-hamburger"];

            $this->parts["menu-hamburger"] = ob_get_clean();
        }

        $page = str_replace("{{menu-hamburger}}", $this->parts["menu-hamburger"], $page);

        if (file_exists($this->files["menu-tiny"])) {
            if (isset($this->vars["menu-tiny"])) {
                extract($this->vars["menu-tiny"]);
            }

            ob_start();

            include_once $this->files["menu-tiny"];

            $this->parts["menu-tiny"] = ob_get_clean();
        }

        $page = str_replace("{{menu-tiny}}", $this->parts["menu-tiny"], $page);

        return str_replace("{{content}}", $this->parts["content"], $page);
    }
}