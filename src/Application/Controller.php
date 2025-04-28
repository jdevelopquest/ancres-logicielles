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

    protected function htmlspecialcharsWalking(string|array &$data): void {
        array_walk($data, function (&$value) {
            if (is_string($value)) {
                $value = htmlspecialchars($value);
            }
        });
    }

    protected function renderTextHTML(string $pageTitle = "Ancres Logicielles", string $contentLayout = "", array $contentParams = [], array $notification = []): string
    {
        $this->viewBuilder->setPageParam("theme", $this->getUserTheme());

        return $this->viewBuilder->renderTextHTML($pageTitle, $contentLayout, $contentParams, $notification);
    }
}