<?php
declare(strict_types=1);

namespace App\Src\Controllers;

use App\Core\Controller;
use App\Core\Response;

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

        $this->setViewComponent("content", "supports/about", [], "page");

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

        $this->setViewComponent("content", "supports/policies", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }
}
