<?php

namespace App\Application;

class ViewBuilder
{
    public array $vars = [];
    protected array $files = [];
    protected array $parts = [];
    public function __construct()
    {
        $this->files["pageLayout"] = $this->constructFilePath("layouts/page");

        $this->parts["content"] = "";
        $this->parts["page"] = "";
    }

    protected function constructFilePath(string $layout): string
    {
        return VIEWS . str_replace("/", DIRECTORY_SEPARATOR, $layout . ".php");
    }

    public function render(): string
    {
        if (file_exists($this->files["pageLayout"])) {
            ob_start();

            include_once $this->files["pageLayout"];

            $this->parts["page"] = ob_get_clean();
        }

        return str_replace('{{content}}', $this->parts["content"], $this->parts["page"]);
    }
}