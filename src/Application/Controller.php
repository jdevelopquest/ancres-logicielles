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

    protected ViewBuilder $viewBuilder;
    protected array $pageParams = [];
    private array $pagePartials = [];

    public function __construct(protected Request $request, protected Response $response)
    {
        $this->viewBuilder = new ViewBuilder();
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
        $this->response->addHeader("Content-Type: text/html");

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
        $this->response->addHeader("Content-Type: application/json");

        $this->response->setCode($code);

        if (isset($data)) {
            $this->response->setBody(json_encode($data));
        }

        return $this->response;
    }

    /**
     * Configures a page partial with the specified parameters and stores it for rendering.
     *
     * @param string $name The name of the partial to identify it.
     * @param string $layout The layout template associated with the partial.
     * @param array $params An array of parameters to be passed to the partial.
     * @param string|null $writeIn Optional target area for rendering the partial. Defaults to an empty string.
     * @return void
     */
    protected function setPagePartial(string $name, string $layout, array $params, ?string $writeIn = ""): void
    {
        $this->pagePartials[$name] = [
            "name" => $name,
            "layout" => $layout,
            "params" => $params,
            "writeIn" => $writeIn,
            "html" => ""
        ];
    }

    /**
     * Renders the complete HTML page by combining defined page partials into the final output.
     *
     * @return string The fully rendered HTML page as a string.
     */
    protected function renderHtmlPage(): string
    {
        $this->prepareHtmlPageForRender();

        foreach ($this->pagePartials as $name => $part) {
            $this->viewBuilder->setPagePartial(
                $part["name"],
                $part["layout"],
                $part["params"],
                $part["writeIn"]
            );
        }

        return $this->viewBuilder->renderHtmlPage($this->pageParams);
    }

    /**
     * Recursively escapes HTML special characters in a string or array.
     *
     * @param array|string|float|int|bool|null $data The input data to be escaped. Can be a string or an array.
     *                            Strings are directly escaped, and arrays are processed recursively.
     *
     * @return void
     */
    protected function escapeHtmlRecursive(array|string|float|int|bool|null &$data): void
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

        $this->setPagePartial("menu-hamburger", "layouts/menu-hamburger", $menuHamburgerParams, "page");
        $this->setPagePartial("menu-tiny", "layouts/menu-tiny", $menuTinyParams, "page");

    }
}