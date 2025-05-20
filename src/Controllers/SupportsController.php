<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\ConstructMenu;

class SupportsController extends Controller
{
    use ConstructMenu;
    public function about(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : À propos de nous");

        $this->setPartConfig("content", "supports/about", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    public function policies(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Chartes");

        $this->setPartConfig("content", "supports/about", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }
}