<?php

namespace App\Application;

use App\Application\Utils\ConstructMenu;
use App\Application\Utils\LogPrinter;
use App\Application\Utils\SessionManager;

class Controller
{
    use SessionManager;
    use ConstructMenu;
    use LogPrinter;

    protected Response $response;
    protected ViewBuilder $viewBuilder;
    protected array $pageParams = [];
    private array $pageParts = [];

    public function __construct(protected Request $request)
    {
        $this->viewBuilder = new ViewBuilder();
        $this->response = new Response();
    }

    /**
     * Generates an HTML response with the specified content and status code.
     *
     * @param string $pageHtml The HTML content to include in the response body.
     * @param int $code The HTTP status code for the response. Defaults to 200.
     * @return Response The response object containing the headers, status code, and body.
     */
    protected function getHtmlResponse(string $pageHtml, int $code = 200): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode($code);

        $this->response->setBody($pageHtml);

        return $this->response;
    }

    /**
     * Prepares and returns a JSON formatted HTTP response.
     *
     * @param string|null $data The data to be encoded into JSON format and included in the response body. If null, the body will not include any data.
     * @param int $code The HTTP status code for the response. Defaults to 200.
     * @return Response The prepared HTTP response with JSON content and defined status code.
     */
    protected function getJsonResponse(?string $data, int $code = 200): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "application/json",
        ]);

        $this->response->setCode($code);

        if (isset($data)) {
            $this->response->setBody(json_encode($data));
        }

        return $this->response;
    }

    /**
     * Configures and sets a part of the page with the specified parameters.
     *
     * @param string $name The name identifier for the page part.
     * @param string $layout The layout template associated with the page part.
     * @param array $params An associative array of parameters to configure the page part.
     * @param string|null $writeIn Optional parameter specifying the target section for the part. Defaults to an empty string.
     *
     * @return void
     */
    protected function setPartConfig(string $name, string $layout, array $params, ?string $writeIn = ""): void
    {
        $this->pageParts[$name] = [
            "name" => $name,
            "layout" => $layout,
            "params" => $params,
            "writeIn" => $writeIn,
            "html" => ""
        ];
    }

    /**
     * Renders the complete HTML page by preparing and assembling its parts.
     *
     * This method processes all defined page parts, generates their HTML using the
     * specified layouts and parameters, and combines them into the final page output.
     *
     * @return string The fully rendered HTML content of the page.
     */
    protected function renderHtmlPage(): string
    {
        $this->prepareHtmlPageForRender();

        $this->setPartConfig("page", "layouts/page", $this->pageParams, null);

        foreach ($this->pageParts as &$part) {
            $part["html"] = $this->renderHtmlPart($part["layout"], $part["params"]);
        }

        $this->processNestedTemplates("page");

        return $this->pageParts["page"]["html"];
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
        $sources = array_keys(array_filter($this->pageParts, function ($part) use ($dest) {
            return $part["writeIn"] === $dest;
        }));

        foreach ($sources as $src) {
            $name = $this->pageParts[$src]["name"];

            $this->processNestedTemplates($this->pageParts[$src]["name"]);

            $this->pageParts[$dest]["html"] = str_replace(
                "{{ $name }}",
                $this->pageParts[$src]["html"],
                $this->pageParts[$dest]["html"]);
        }
    }

    /**
     * Renders a specific HTML part using the provided layout and parameters.
     *
     * @param string $partLayout The layout template to use for rendering the HTML part.
     * @param array $partParams An associative array of parameters to be passed to the layout for rendering.
     *
     * @return string The rendered HTML content as a string.
     */
    protected function renderHtmlPart(string $partLayout, array $partParams): string
    {
        return $this->viewBuilder->renderPart($partLayout, $partParams);
    }

    /**
     * Recursively escapes HTML special characters in a string or array.
     *
     * @param string|array &$data The input data to be escaped. Can be a string or an array.
     *                            Strings are directly escaped, and arrays are processed recursively.
     *
     * @return void
     */
    protected function escapeHtmlRecursive(string|array &$data): void
    {
        if (is_string($data)) {
            $data = htmlspecialchars($data);
        } else if (is_array($data)) {
            array_walk($data, function (&$value) {
                $this->escapeHtmlRecursive($value);
            });
        }
    }

    /**
     * Sets a parameter for the page with the specified name and value.
     *
     * @param string $name The key or name of the page parameter to set.
     * @param string $value The value to assign to the specified page parameter.
     *
     * @return void
     */
    protected function setPageParam(string $name, string $value): void
    {
        $this->pageParams[$name] = $value;
    }

    /**
     * Checks if a specific page parameter is set.
     *
     * @param string $name The name of the parameter to check for existence.
     *
     * @return bool Returns true if the parameter exists, false otherwise.
     */
    protected function issetPageParam(string $name): bool
    {
        return isset($this->pageParams[$name]);
    }

    /**
     * Prepares the HTML page's parameters for rendering by setting page-specific configurations.
     *
     * This method adjusts parameters such as the previous page link, the page title, the theme,
     * and settings related to the menu for proper rendering.
     *
     * @return void
     */
    private function prepareHtmlPageForRender(): void
    {
        // page précédente
        $previousPage = $this->getUserPreviousPage();

        if ($previousPage) {
            $this->setPageParam("previousPage", $previousPage);
        }

        // met à jour page précédente
        $previousPage = $this->request->getPath() . "?" . $this->request->getQuery();

        $this->setUserPreviousPage($previousPage);

        // titre de la page
        if ($this->issetPageParam("title")) {
            $this->setPageParam("title", "Ancres Logicielles");
        }

        // thème de la page
        $this->setPageParam("theme", $this->getUserTheme());

        // menu hamburger et menu tiny
        $this->setupHamburgerAndTinyParams();
    }

    /**
     * Sets up and configures the parameters for the hamburger menu and tiny menu.
     *
     * This method constructs the parameters required for both menus and assigns
     * their configurations to the respective layouts.
     *
     * @return void
     */
    private function setupHamburgerAndTinyParams(): void
    {
        $menuHamburgerParams = $this->constructMenuHamburgerParams();
        $menuTinyParams = $this->constructMenuTinyParams();

        $this->setPartConfig("menu-hamburger", "layouts/menu-hamburger", $menuHamburgerParams, "page");
        $this->setPartConfig("menu-tiny", "layouts/menu-tiny", $menuTinyParams, "page");

    }
}