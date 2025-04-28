<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class AccountsController extends Controller
{
    public function signin(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles : Inscription","accounts/signin"));

        return $this->response;
    }
}