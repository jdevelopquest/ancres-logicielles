<?php

namespace App\Application;

use App\Application\Utils\ConstructMenu;

class ViewBuilder
{
    use ConstructMenu;

    protected array $partNames = [];
    protected array $partParams = [];
    protected array $partLayouts = [];
    protected array $partRenders = [];

    public function __construct()
    {
    }

    protected function constructFilePath(string $layout): string
    {
        return VIEWS . str_replace("/", DIRECTORY_SEPARATOR, $layout . ".php");
    }

    protected function setupPart(string $partName, string $partLayout, mixed $partParams): void
    {
        $this->partNames[] = $partName;
        $this->partParams[$partName] = $partParams;
        $this->partLayouts[$partName] = $this->constructFilePath($partLayout);
        $this->partRenders[$partName] = "";
    }

    public function set(string $key, mixed $value): void
    {
        $this->partParams[$key] = $value;
    }

    public function renderTextHTML(string $pageTitle = "Ancres Logicielles", string $contentLayout = "", mixed $contentParams = []): string
    {
        $this->setupPart("page", "layouts/page", []);
        $this->setupPart("menu-hamburger", "layouts/menu-hamburger", $this->constructMenuHamburger());
        $this->setupPart("menu-tiny", "layouts/menu-tiny", $this->constructMenuTiny());
        $this->setupPart("content", $contentLayout, $contentParams);

        $this->partParams["page"]["title"] = $pageTitle;

        // rendre la page en premier
        $this->renderPart("page");

        foreach ($this->partNames as $partName) {
            if ($partName === "page") {
                continue;
            }

            $this->renderPart($partName);
            $this->partRenders["page"] = str_replace("{{ $partName }}", $this->partRenders[$partName], $this->partRenders["page"]);
        }

        return $this->partRenders["page"];
    }

    protected function renderPart(string $partName): void
    {
        if (file_exists($this->partLayouts[$partName])) {
            if (isset($this->partParams[$partName])) {
                extract($this->partParams[$partName]);
            }

            ob_start();

            include_once $this->partLayouts[$partName];

            $this->partRenders[$partName] = ob_get_clean();
        }
    }
}