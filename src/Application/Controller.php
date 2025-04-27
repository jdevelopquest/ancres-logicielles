<?php

namespace App\Application;

class Controller
{
    protected Response $response;
    protected ViewBuilder $viewBuilder;
    public function __construct(?string $contentLayout)
    {
        $this->response = new Response();
        $this->viewBuilder = new ViewBuilder($contentLayout);
    }
}