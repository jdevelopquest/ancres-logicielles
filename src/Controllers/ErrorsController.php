<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class ErrorsController extends Controller
{
    public function error400(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(400);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Erreur", "errors/error400"));

        return $this->response;
    }

    public function error403(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(403);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Erreur", "errors/error403"));

        return $this->response;
    }

    public function error404(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(404);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Erreur", "errors/error404"));

        return $this->response;
    }

    public function error500(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(500);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Erreur", "errors/error500"));

        return $this->response;
    }

    public function error503(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(503);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Erreur", "errors/error503"));

        return $this->response;
    }

    public function error404ByAjax(): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $this->response->setCode(404);

        return $this->response;
    }

    public function error503ByAjax(): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $this->response->setCode(503);

        return $this->response;
    }
}