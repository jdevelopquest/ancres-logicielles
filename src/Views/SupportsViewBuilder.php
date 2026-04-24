<?php

namespace App\Views;

use App\Application\ViewBuilder;

class SupportsViewBuilder extends ViewBuilder
{
    public function about(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("supports/about");

        if (file_exists($this->files["contentLayout"])) {
            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->renderTextHTML();
    }

    public function policies(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("supports/policies");

        if (file_exists($this->files["contentLayout"])) {
            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->renderTextHTML();
    }
}