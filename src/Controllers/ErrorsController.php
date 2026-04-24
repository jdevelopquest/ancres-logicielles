<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Views\ErrorsViewBuilder;

class ErrorsController extends Controller
{
    public function error404(): Response
    {
        $errorsViewBuilder = new ErrorsViewBuilder();
        $errorsViewBuilder->addTitle("Ancres Logicielles : Erreur page non trouvée");

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(404);

        $this->response->setBody($errorsViewBuilder->render404());

        return $this->response;
    }

    public function error500(): Response
    {
        $errorsViewBuilder = new ErrorsViewBuilder();
        $errorsViewBuilder->addTitle("Ancres Logicielles : Erreur interne");

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(500);

        $this->response->setBody($errorsViewBuilder->render500());

        return $this->response;
    }
}