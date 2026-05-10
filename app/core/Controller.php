<?php
declare(strict_types=1);

namespace App\Core;

use App\CoreUtils\UserInterface\ConstructHref;
use App\CoreUtils\UserInterface\Menu;
use App\CoreUtils\Session\SessionManager;

class Controller
{
    use SessionManager;
    use ConstructHref;

    private ViewBuilder $viewBuilder;

    public function __construct(
        protected Request $request,
        protected Response $response,
    ) {
        $this->viewBuilder = new ViewBuilder();
    }

    /**
     * Generates an HTML response with the specified content and status code.
     *
     * @param string $pageHtml The HTML content to include in the response body.
     * @param int $code The HTTP status code for the response. Defaults to 200.
     * @return Response The response object containing the headers, status code, and body.
     */
    protected function getHtmlResponse(
        string $pageHtml = "",
        int $code = 200,
    ): Response {
        return $this->response
            ->addHeader("Content-Type: text/html")
            ->setCode($code)
            ->setBody($pageHtml);
    }

    /**
     * Prepares and returns a JSON formatted HTTP response.
     *
     * @param mixed $data The data to be encoded into JSON format and included in the response body. If null, the body will not include any data.
     * @param int $code The HTTP status code for the response. Defaults to 200.
     * @return Response The prepared HTTP response with JSON content and defined status code.
     */
    protected function getJsonResponse(
        mixed $data = null,
        int $code = 200,
    ): Response {
        if (is_string($data)) {
            $data = iconv("UTF-8", "UTF-8//IGNORE", $data);
        }

        $data = json_encode($data);

        return $this->response
            ->addHeader("Content-Type: application/json")
            ->setCode($code)
            ->setBody($data);
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
    protected function setViewComponent(
        string $name,
        string $layout,
        array $params,
        ?string $writeIn = "",
    ): void {
        $this->viewBuilder->setViewComponent($name, $layout, $params, $writeIn);
    }

    /**
     * Renders an HTML partial view using the specified file path and parameters.
     *
     * @param string $partialFilePath The file path of the partial view to be rendered. Defaults to an empty string.
     * @param array $parameters An associative array of parameters to pass to the partial view.
     * @return string The rendered HTML content of the partial view.
     */
    protected function renderHtmlComponent(
        string $partialFilePath = "",
        array $parameters = [],
    ): string {
        return $this->viewBuilder->renderComponent(
            $partialFilePath,
            $parameters,
        );
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
            $this->viewBuilder->setViewComponent(
                "page",
                "layouts/page",
                ["title" => "Ancres Logicielles"],
                null,
            );
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
        // menu hamburger et menu tiny
        $this->setupHamburgerAndTinyMenus();
    }

    /**
     * Configures and initializes the hamburger and tiny menus for the application's navigation system.
     *
     * Creates and sets up menus based on user roles (guest, registered, or admin) and dynamically links
     * appropriate options such as Home, Profile, Login, Logout, Signup, or Administration. Each menu is
     * assigned to a layout for rendering within the view.
     *
     * @return void This method does not return a value.
     */
    private function setupHamburgerAndTinyMenus(): void
    {
        $menuConfigurations = [
            ["layoutName" => "menu-hamburger", "menuName" => "menuHamburger"],
            ["layoutName" => "menu-tiny", "menuName" => "menuTiny"],
        ];

        foreach ($menuConfigurations as $configuration) {
            $menu = new Menu($configuration["menuName"])
                ->addSubMenu("homepage")
                ->addSubMenuItem(
                    $this->constructHref("posts", "indexSoftwares"),
                    "Accueil",
                    "Accueil",
                    "go-home",
                );

            if ($this->userIsLoggedIn()) {
                $menu
                    ->addSubMenu("registeredMenu")
                    ->addSubMenuItem(
                        $this->constructHref(
                            "accounts",
                            "show",
                            $this->getUserId(),
                        ),
                        "Profil",
                        "Profil",
                        "go-profile",
                    )
                    ->addSubMenuItem(
                        $this->constructHref("accounts", "logout"),
                        "Déconnexion",
                        "Déconnexion",
                        "go-logout",
                    );
            } else {
                $menu
                    ->addSubMenu("guestMenu")
                    ->addSubMenuItem(
                        $this->constructHref("accounts", "login"),
                        "Connexion",
                        "Connexion",
                        "go-login",
                    )
                    ->addSubMenuItem(
                        $this->constructHref("accounts", "signup"),
                        "Inscription",
                        "Inscription",
                        "go-signup",
                    );
            }

            if ($this->userIsAdmin()) {
                $menu
                    ->addSubMenu("adminMenu")
                    ->addSubMenuItem(
                        $this->constructHref("admins", "index"),
                        "Administration",
                        "Administration",
                        "go-admin",
                    );
            }

            $this->setViewComponent(
                $configuration["layoutName"],
                "layouts/" . $configuration["layoutName"],
                $menu->getMenu(),
                "page",
            );
        }
    }
}
