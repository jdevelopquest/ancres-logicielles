<?php

namespace App\Application;

class ViewBuilder
{
    /**
     * Constructs the file path for a given layout by replacing forward slashes with
     * the system's directory separator and appending the file extension.
     *
     * @param string $layout The layout name or path relative to the views directory.
     * @return string The constructed file path with the correct directory separators and extension.
     */
    protected function constructFilePath(string $layout): string
    {
        return VIEWS_PATH . str_replace("/", DIRECTORY_SEPARATOR, $layout . ".php");
    }

    /**
     * Renders a specific part of a layout by including the corresponding file and optionally passing parameters.
     *
     * @param string $partLayout The layout name or path relative to the views directory. Defaults to an empty string.
     * @param array $partParams An associative array of parameters to extract and make available within the layout file. Defaults to an empty array.
     * @return string The rendered content of the included layout file, or an empty string if the file does not exist.
     */
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