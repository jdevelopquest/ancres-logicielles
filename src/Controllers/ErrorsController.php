<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class ErrorsController extends Controller
{
    public function error404(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(404);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Erreur page non trouvée","errors/error404"));

        return $this->response;
    }

    public function error500(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(500);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Erreur interne","errors/error500"));

        return $this->response;
    }
}