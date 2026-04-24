<?php

namespace App\Application;

class Controller
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }
}