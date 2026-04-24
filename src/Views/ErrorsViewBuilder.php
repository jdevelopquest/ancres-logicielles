<?php

namespace App\Views;

use App\Application\ViewBuilder;

class ErrorsViewBuilder extends ViewBuilder
{
    public function render404(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("errors/error404");

        if (file_exists($this->files["contentLayout"])) {
            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->render();
    }

    public function render500(): string
    {
        $this->files["contentLayout"] = $this->constructFilePath("errors/error500");

        if (file_exists($this->files["contentLayout"])) {
            ob_start();

            include_once $this->files["contentLayout"];

            $this->parts["content"] = ob_get_clean();
        }

        return $this->render();
    }
}