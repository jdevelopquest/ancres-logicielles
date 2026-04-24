<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

class ArticlesController extends Controller
{
    public function index(): Response
    {
//        $articles = new ArticlesModel();
//        $articles = $articles->getArticles();

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles","articles/index"));

        return $this->response;
    }
}