<?php

namespace App\Application;

class ViewBuilder
{
    private array $viewComponents = [];
    
    /**
     * Constructs the full file path for the given layout file.
     *
     * @param string $layoutFilePath The relative file path of the layout, using forward slashes as separators.
     * @return string The absolute file path of the layout, with directory separators adjusted for the current system.
     */
    private function constructFilePath(string $layoutFilePath): string
    {
        return VIEWS_PATH . str_replace("/", DIRECTORY_SEPARATOR, $layoutFilePath . ".php");
    }

    /**
     * Renders a component and returns its content as a string.
     *
     * @param string $templatePath The file path of the partial to be included. Defaults to an empty string.
     * @param array $parameters An associative array of parameters to extract and make available within the scope of the partial. Defaults to an empty array.
     * @return string The rendered content of the partial file, or an empty string if the file does not exist.
     */
    public function renderComponent(string $templatePath = "", array $parameters = []): string
    {
        $filePath = $this->constructFilePath($templatePath);

        if (file_exists($filePath)) {
            if (isset($parameters)) {
                extract($parameters);
            }

            // Start output buffering (if not already started)
            ob_start();

            require $filePath;

            return ob_get_clean();
        }

        return "";
    }

    /**
     * Defines a view component with the specified parameters.
     *
     * @param string $name The name of the component to identify it.
     * @param string $layout The layout template associated with the component.
     * @param array $params An array of parameters to be passed to the component.
     * @param string|null $writeIn Optional target area for rendering the component.
     * @return void
     */
    public function setViewComponent(string $name, string $layout, array $params, ?string $writeIn = ""): void
    {
        $this->viewComponents[$name] = [
            "name" => $name,
            "layout" => $layout,
            "params" => $params,
            "writeIn" => $writeIn,
            "html" => ""
        ];
    }

    /**
     * Adds a parameter to a specified view component.
     *
     * @param string $name The name of the view component to which the parameter will be added.
     * @param string $key The key of the parameter to add.
     * @param mixed $value The value of the parameter to add.
     * @return void
     */
    public function addViewComponentParam(string $name, string $key, mixed $value): void
    {
        // todo: déclencher une erreur ou renvoyer une valeur si le composant n'existe pas
        if (isset($this->viewComponents[$name])) {
            $this->viewComponents[$name]["params"][$key] = $value;
        }
    }

    /**
     * Checks if a view component parameter with the given name exists.
     *
     * @param string $name The name of the view component parameter to check.
     * @return bool Returns true if the view component parameter exists, false otherwise.
     */
    public function issetViewComponentParam(string $name): bool
    {
        return isset($this->viewComponents[$name]);
    }

    /**
     * Renders the HTML content of the page by processing all view components,
     * including nested components, and returning the final compiled HTML.
     *
     * @return string The final HTML content of the rendered page.
     */
    public function renderHtmlPage(): string
    {
        if (!isset($this->viewComponents["page"])) {
            $this->setViewComponent("page", "layouts/page", []);
        }

        // Render all components
        foreach ($this->viewComponents as &$part) {
            $part["html"] = $this->renderComponent($part["layout"], $part["params"]);
        }

        // Process nested templates
        $this->processNestedComponents("page");

        return $this->viewComponents["page"]["html"];
    }

    /**
     * Processes nested components by recursively resolving and replacing placeholders
     * in the HTML content of components.
     *
     * @param string $dest The destination component name for which nested components
     *                      will be resolved and rendered.
     * @return void
     */
    private function processNestedComponents(string $dest): void
    {
        $sources = array_keys(array_filter($this->viewComponents, function ($part) use ($dest) {
            return $part["writeIn"] === $dest;
        }));

        foreach ($sources as $src) {
            $name = $this->viewComponents[$src]["name"];

            $this->processNestedComponents($this->viewComponents[$src]["name"]);

            $this->viewComponents[$dest]["html"] = str_replace(
                "{{ $name }}",
                $this->viewComponents[$src]["html"],
                $this->viewComponents[$dest]["html"]);
        }
    }
}