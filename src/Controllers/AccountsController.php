<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class AccountsController extends Controller
{
    public function signup(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Inscription","accounts/signup"));

        return $this->response;
    }

    public function login(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Connexion","accounts/login"));

        return $this->response;
    }

    public function logout(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Déconnexion","accounts/logout"));

        return $this->response;
    }
}