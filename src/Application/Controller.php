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

    private ViewBuilder $viewBuilder;

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
     * Configures a view component with the specified name, layout, parameters, and optional write location.
     *
     * @param string $name The name of the view component to set.
     * @param string $layout The layout template to use for the view component.
     * @param array $params An associative array of parameters to pass to the view component.
     * @param string|null $writeIn Optional. The location where the view component should be rendered. Defaults to an empty string.
     * @return void This method does not return a value.
     */
    protected function setViewComponent(string $name, string $layout, array $params, ?string $writeIn = ""): void
    {
        $this->viewBuilder->setViewComponent($name, $layout, $params, $writeIn);
    }

    /**
     * Renders an HTML partial view using the specified file path and parameters.
     *
     * @param string $partialFilePath The file path of the partial view to be rendered. Defaults to an empty string.
     * @param array $parameters An associative array of parameters to pass to the partial view.
     * @return string The rendered HTML content of the partial view.
     */
    protected function renderHtmlComponent(string $partialFilePath = "", array $parameters = []): string
    {
        return $this->viewBuilder->renderComponent($partialFilePath, $parameters);
    }

    /**
     * Renders the complete HTML page by combining defined page partials into the final output.
     *
     * @return string The fully rendered HTML page as a string.
     */
    protected function renderHtmlPage(): string
    {
        $this->prepareHtmlPageForRender();

        return $this->viewBuilder->renderHtmlPage();
    }

    /**
     * Sets a parameter for the page by assigning a key-value pair to the view component.
     *
     * This method allows specifying custom parameters that can be accessed within the view,
     * enabling dynamic control over content or configuration for the page.
     *
     * @param string $key The parameter name to be set.
     * @param string $value The value to associate with the specified parameter name.
     * @return void This method does not return a value.
     */
    protected function setPageParam(string $key, string $value): void
    {
        if (!$this->viewBuilder->issetViewComponentParam("page")) {
            $this->viewBuilder->setViewComponent("page", "layouts/page", ["title" => "Ancres Logicielles"], null);
        }

        $this->viewBuilder->addViewComponentParam("page", $key, $value);
    }

    /**
     * Prepares the necessary parameters for rendering an HTML page, including user navigation data,
     * title, and menu configuration.
     *
     * Updates the user's previous page data, sets the page title if not already defined, and configures
     * specific parameters for the menu structure.
     *
     * @return void This method does not return a value.
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

        $this->setViewComponent("menu-hamburger", "layouts/menu-hamburger", $menuHamburgerParams, "page");
        $this->setViewComponent("menu-tiny", "layouts/menu-tiny", $menuTinyParams, "page");

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
}