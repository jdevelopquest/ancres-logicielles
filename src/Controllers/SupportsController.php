<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
class SupportsController extends Controller
{
    /**
     * Sets up the "About Us" page by configuring its parameters and content.
     *
     * @return Response Rendered HTML response for the "About Us" page.
     */
    public function about(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : À propos de nous");

        $this->setPagePartial("content", "supports/about", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    /**
     * Sets the title and content configuration for the policies page and returns an HTML response.
     *
     * @return Response The generated HTML response for the policies page.
     */
    public function policies(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Chartes");

        $this->setPagePartial("content", "supports/about", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }
}