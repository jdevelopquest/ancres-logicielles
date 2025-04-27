<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Views\ArticlesViewBuilder;

class ArticlesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): Response
    {
//        $articles = new ArticlesModel();
//        $articles = $articles->getArticles();

        $articlesViewBuilder = new ArticlesViewBuilder();
        $articlesViewBuilder->addTitle("Ancres Logicielles : Accueil");

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($articlesViewBuilder->index());

        return $this->response;
    }
}