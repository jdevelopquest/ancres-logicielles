<?php

namespace App\Application;

class ViewBuilder
{
    protected function constructFilePath(string $layout): string
    {
        return VIEWS . str_replace("/", DIRECTORY_SEPARATOR, $layout . ".php");
    }

    public function renderPart(string $partLayout = "", array $partParams = []): string
    {
        $file = $this->constructFilePath($partLayout);

        if (file_exists($file)) {
            if (isset($partParams)) {
                extract($partParams);
            }

            ob_start();

            include_once $file;

            return ob_get_clean();
        }

        return "";
    }
}