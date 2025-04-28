<?php

namespace App\Application;

use App\Application\Utils\SessionManager;

class Controller
{
    use SessionManager;
    protected ViewBuilder $viewBuilder;
    protected Response $response;

    public function __construct(protected Request $request)
    {
        $this->viewBuilder = new ViewBuilder();
        $this->response = new Response();
    }

    public function renderTextHTML(string $pageTitle = "Ancres Logicielles", string $contentLayout = "", mixed $contentParams = []): string
    {
        $this->viewBuilder->setPageParam("theme", $this->getUserTheme());

        return $this->viewBuilder->renderTextHTML($pageTitle, $contentLayout, $contentParams);
    }
}