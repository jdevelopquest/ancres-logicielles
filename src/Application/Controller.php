<?php

namespace App\Application;

use App\Application\Utils\ConstructHref;
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
        if (is_string($data)) {
            $data = htmlspecialchars($data);
        } else if (is_array($data)) {
            array_walk($data, function (&$value) {
                if (is_string($value)) {
                    $value = htmlspecialchars($value);
                }
            });
        }
    }

    protected function renderPage(string $pageTitle = "Ancres Logicielles", string $contentLayout = "", array $contentParams = [], array $notification = []): string
    {
        if (!$this->request->isAjax()) {
            $previousPage = $this->getUserPreviousPage();

            if ($previousPage) {
                $this->viewBuilder->setPageParam("previousPage", $previousPage);
            }
            
            $previousPage = $this->request->getPath() . "?" . $this->request->getQuery();
            $this->setUserPreviousPage($previousPage);
        }

        $this->viewBuilder->setPageParam("title", $pageTitle);
        $this->viewBuilder->setPageParam("theme", $this->getUserTheme());

        return $this->viewBuilder->renderPage($contentLayout, $contentParams, $notification);
    }

    protected function renderPart(string $partLayout, array $partParams): string
    {
        return $this->viewBuilder->renderPart($partLayout, $partParams);
    }
}