<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\SessionManager;
use App\Models\PostModel;
use Exception;

class ArticlesController extends Controller
{
    use SessionManager;

    public function index(): Response
    {
        $articles = [];
        $contentParams = [];

        $post = new PostModel();

        if ($this->userIsGuest()) {
            try {
                $articles = $post->getSoftwaresPublished();
            } catch (Exception $e) {
                $contentParams["error"] = "Impossible de récupérer les articles.";
            }
        }
//
//        var_dump($articles);
//        var_dump($contentParams);

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles","articles/index"));

        return $this->response;
    }
}