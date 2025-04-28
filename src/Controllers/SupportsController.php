<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class SupportsController extends Controller
{
    public function about(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : À propos de nous","supports/about"));

        return $this->response;
    }

    public function policies(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Chartes","supports/policies"));

        return $this->response;
    }
}