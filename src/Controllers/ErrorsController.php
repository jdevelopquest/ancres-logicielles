<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class ErrorsController extends Controller
{
    public function __construct()
    {
        parent::__construct("errors/error404");
    }

    public function error404(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(404);

        $this->response->setBody($this->viewBuilder->render());

        return $this->response;
    }
}