<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class ErrorsController extends Controller
{
    public function error400(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Erreur");

        $this->setPartConfig("content", "errors/error400", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 400);
    }

    public function error403(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Erreur");

        $this->setPartConfig("content", "errors/error403", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 403);
    }

    public function error404(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Erreur");

        $this->setPartConfig("content", "errors/error404", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 404);
    }

    public function error500(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Erreur");

        $this->setPartConfig("content", "errors/error500", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 500);
    }

    public function error503(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Erreur");

        $this->setPartConfig("content", "errors/error503", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 503);
    }

    public function error404ByAjax(): Response
    {
        return $this->getJsonResponse(null, 404);
    }

    public function error503ByAjax(): Response
    {
        return $this->getJsonResponse(null, 503);
    }
}