<?php

namespace App\Application;

class ViewBuilder
{
    public array $vars = [];
    protected array $files = [];
    protected array $parts = [];
    public function __construct(protected ?string $contentLayout)
    {
        $this->files["pageLayout"] = VIEWS . str_replace("/", DIRECTORY_SEPARATOR, "layouts/page" . ".php");
        $this->files["contentLayout"] = VIEWS . str_replace("/", DIRECTORY_SEPARATOR, $this->contentLayout . ".php");

        $this->parts["content"] = "";
        $this->parts["page"] = "";
    }

    public function render(): string
    {
        if (file_exists($this->files["contentLayout"])) {
            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        if (file_exists($this->files["pageLayout"])) {
            ob_start();

            include_once $this->files["pageLayout"];

            $this->parts["page"] = ob_get_clean();
        }

        return str_replace('{{content}}', $this->parts["content"], $this->parts["page"]);
    }
}