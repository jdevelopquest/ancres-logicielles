<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;

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
//        require ROOT . '/src/Views/articles/index.php';
        $this->response->setCode(200);
        $this->response->setBody("Hello World!");

        return $this->response;
    }
}