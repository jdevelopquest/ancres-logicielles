<?php

namespace App\Application;

class ViewBuilder
{
    private array $pagePartials = [];
    
    /**
     * Constructs the full file path for the given layout file.
     *
     * @param string $layoutFilePath The relative file path of the layout, using forward slashes as separators.
     * @return string The absolute file path of the layout, with directory separators adjusted for the current system.
     */
    protected function constructFilePath(string $layoutFilePath): string
    {
        return VIEWS_PATH . str_replace("/", DIRECTORY_SEPARATOR, $layoutFilePath . ".php");
    }

    /**
     * Renders a partial file and returns its content as a string.
     *
     * @param string $partialFilePath The file path of the partial to be included. Defaults to an empty string.
     * @param array $parameters An associative array of parameters to extract and make available within the scope of the partial. Defaults to an empty array.
     * @return string The rendered content of the partial file, or an empty string if the file does not exist.
     */
    public function renderPartial(string $partialFilePath = "", array $parameters = []): string
    {
        $filePath = $this->constructFilePath($partialFilePath);

        if (file_exists($filePath)) {
            if (isset($parameters)) {
                extract($parameters);
            }

            ob_start();

            include_once $filePath;

            return ob_get_clean();
        }

        return "";
    }
    
    /**
     * Defines a page partial with the specified parameters.
     *
     * @param string $name The name of the partial to identify it.
     * @param string $layout The layout template associated with the partial.
     * @param array $params An array of parameters to be passed to the partial.
     * @param string|null $writeIn Optional target area for rendering the partial.
     * @return self For method chaining
     */
    public function setPagePartial(string $name, string $layout, array $params, ?string $writeIn = ""): self
    {
        $this->pagePartials[$name] = [
            "name" => $name,
            "layout" => $layout,
            "params" => $params,
            "writeIn" => $writeIn,
            "html" => ""
        ];
        
        return $this;
    }
    
    /**
     * Renders the complete HTML page by preparing and assembling its parts.
     *
     * @param array $pageParams Main parameters for the page template
     * @return string The fully rendered HTML content of the page.
     */
    public function renderHtmlPage(array $pageParams): string
    {
        // Set the main page template
        $this->setPagePartial("page", "layouts/page", $pageParams, null);

        // Render all partials
        foreach ($this->pagePartials as &$part) {
            $part["html"] = $this->renderPartial($part["layout"], $part["params"]);
        }

        // Process nested templates
        $this->processNestedTemplates("page");

        return $this->pagePartials["page"]["html"];
    }
    
    /**
     * Processes nested templates by resolving and embedding the HTML content
     * of child templates into their parent template specified by the destination.
     *
     * @param string $dest The name identifier of the destination template where
     * nested templates are embedded.
     *
     * @return void
     */
    private function processNestedTemplates(string $dest): void
    {
        $sources = array_keys(array_filter($this->pagePartials, function ($part) use ($dest) {
            return $part["writeIn"] === $dest;
        }));

        foreach ($sources as $src) {
            $name = $this->pagePartials[$src]["name"];

            $this->processNestedTemplates($this->pagePartials[$src]["name"]);

            $this->pagePartials[$dest]["html"] = str_replace(
                "{{ $name }}",
                $this->pagePartials[$src]["html"],
                $this->pagePartials[$dest]["html"]);
        }
    }
}