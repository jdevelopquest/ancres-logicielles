<?php

namespace App\Application;

class Controller
{
    protected ViewBuilder $viewBuilder;
    protected Response $response;

    public function __construct(protected Request $request)
    {
        $this->viewBuilder = new ViewBuilder();
        $this->response = new Response();
    }
}