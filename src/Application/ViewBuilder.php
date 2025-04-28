<?php

namespace App\Application;

use App\Application\Utils\ConstructMenu;

class ViewBuilder
{
    use ConstructMenu;

    protected array $pageParams = [];
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

    public function setPageParam(string $key, mixed $value): void
    {
        $this->pageParams[$key] = $value;
    }

    public function renderTextHTML(string $pageTitle = "Ancres Logicielles", string $contentLayout = "", array $contentParams = [], array $notification = []): string
    {
        $this->setupPart("page", "layouts/page", []);
        $this->setupPart("menu-hamburger", "layouts/menu-hamburger", $this->constructMenuHamburger());
        $this->setupPart("menu-tiny", "layouts/menu-tiny", $this->constructMenuTiny());
        $this->setupPart("content", $contentLayout, $contentParams ?? []);

        if (isset($notification)) {
            if (array_key_exists("error", $notification)) {
                $this->setupPart("notification", "layouts/error-notification", $notification);
            }

            if (array_key_exists("success", $notification)) {
                $this->setupPart("notification", "layouts/success-notification", $notification);
            }
        }

        $this->partParams["page"]["title"] = $pageTitle;

        if (!empty($this->pageParams)) {
            $this->partParams["page"] = array_merge($this->partParams["page"], $this->pageParams);
        }

        foreach ($this->partNames as $partName) {
            $this->renderPart($partName);
        }

        if (isset($this->partRenders["notification"])) {
            $this->partRenders["content"] = str_replace("{{ notification }}", $this->partRenders["notification"], $this->partRenders["content"]);
        } else {
            $this->partRenders["content"] = str_replace("{{ notification }}", "", $this->partRenders["content"]);
        }

        $this->partRenders["page"] = str_replace("{{ content }}", $this->partRenders["content"], $this->partRenders["page"]);
        $this->partRenders["page"] = str_replace("{{ menu-hamburger }}", $this->partRenders["menu-hamburger"], $this->partRenders["page"]);
        $this->partRenders["page"] = str_replace("{{ menu-tiny }}", $this->partRenders["menu-tiny"], $this->partRenders["page"]);

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