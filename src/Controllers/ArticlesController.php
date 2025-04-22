<?php

namespace App\Controllers;
use App\Application\Viewer;
use App\Models\ArticlesModel;
class ArticlesController
{
    public function __construct(private Viewer $viewer)
    {
    }

    public function index() :void
    {
        $articles = new ArticlesModel();
        $articles = $articles->getArticles();
        require ROOT . '/src/Views/articles/index.php';
    }
}