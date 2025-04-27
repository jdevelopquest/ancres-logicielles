<?php

namespace App\Views;

use App\Application\ViewBuilder;

class ArticlesViewBuilder extends ViewBuilder
{
    public function index(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("articles/index");

        if (file_exists($this->files["contentLayout"])) {
            if (isset($this->vars["content"])) {
                extract($this->vars["content"]);
            }

            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->renderTextHTML();
    }

    public function show(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("articles/show");

        if (file_exists($this->files["contentLayout"])) {
            if (isset($this->vars["content"])) {
                extract($this->vars["content"]);
            }

            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->renderTextHTML();
    }
}