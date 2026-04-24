<?php

namespace App\Application;

class Controller
{
    protected ViewBuilder $viewBuilder;
    protected Response $response;

    public function __construct()
    {
        $this->viewBuilder = new ViewBuilder();
        $this->response = new Response();
    }
}