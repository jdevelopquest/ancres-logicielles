<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\ConstructMenu;
use App\Views\SupportsViewBuilder;

class SupportsController extends Controller
{
    use ConstructMenu;
    public function about(): Response
    {
        $supportsViewBuilder = new SupportsViewBuilder();
        $supportsViewBuilder->addTitle("Ancres Logicielles : À propos");

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($supportsViewBuilder->about());

        return $this->response;
    }

    public function policies(): Response
    {
        $supportsViewBuilder = new SupportsViewBuilder();
        $supportsViewBuilder->addTitle("Ancres Logicielles : Chartes");

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($supportsViewBuilder->policies());

        return $this->response;
    }
}