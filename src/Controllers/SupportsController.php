<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Views\SupportsViewBuilder;

class SupportsController extends Controller
{
    public function about(): Response
    {
        $supportsViewBuilder = new SupportsViewBuilder();

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

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($supportsViewBuilder->policies());

        return $this->response;
    }
}