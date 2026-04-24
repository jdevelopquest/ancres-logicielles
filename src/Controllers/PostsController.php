<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\ConstructHref;
use App\Application\Utils\SessionManager;
use App\Models\PostModel;
use Exception;

class PostsController extends Controller
{
    use SessionManager;
    use ConstructHref;

    public function indexSoftwares(): Response
    {
        $softwares = [];

        $post = new PostModel();

        if ($this->userIsGuest()) {
            try {
                $softwares = $post->getSoftwaresPublished();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        if ($this->userIsRegistered()) {
            try {
                $softwares = $post->getSoftwaresPublishedAndPending();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            try {
                $softwares = $post->getSoftwares();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        $contentParams = [];

        if ($softwares) {
            $contentParams["softwares"] = [];

            foreach ($softwares as $software) {
                array_walk($software, function (&$value) {
                    $value = htmlspecialchars($value);
                });

                $article["href"] = $this->constructHref("posts", "showSoftware", $software["idPost"]);
                $article["softwareName"] = $software["softwareName"];
                $article["status"] = [];

                if ($software["postIsPublished"]) {
                    $article["status"][] = [
                        "icon" => "published",
                        "title" => "publiée"
                    ];
                } else {
                    $article["status"][] = [
                        "icon" => "pending",
                        "title" => "en attente"
                    ];
                }

                if ($software["postIsBanned"]) {
                    $article["status"][] = [
                        "icon" => "banned",
                        "title" => "banni"
                    ];
                }

                $contentParams["softwares"][] = $article;
            }
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles", "posts/indexSoftwares", $contentParams));

        return $this->response;
    }

    public function showSoftware(string $idPost): Response
    {
        $post = new PostModel();

        try {
            $software = $post->getSoftwareByIdPost($idPost);

            // idPost est inexistant
            if (!$software) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error404();
            }
        } catch (Exception $e) {
            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503();
        }

        if ($this->userIsGuest() && !$software["postIsPublished"]) {
            $errorsController = new ErrorsController($this->request);
            return $errorsController->error403();
        }

        if ($this->userIsRegistered() && $software["postIsBanned"]) {
            $errorsController = new ErrorsController($this->request);
            return $errorsController->error403();
        }

        array_walk($software, function (&$value) {
            $value = htmlspecialchars($value);
        });

        $contentParams = [];
        $contentParams["software"] = [];
        $contentParams["software"]["softwareName"] = $software["softwareName"];

        $article["summary"] = [];
        foreach (explode("\n", $software["softwareSummary"]) as $summaryPart) {
            $contentParams["software"]["summary"][] = $summaryPart;
        }

        $contentParams["software"]["status"] = [];

        if ($software["postIsPublished"]) {
            $contentParams["software"]["status"][] = [
                "icon" => "published",
                "title" => "publiée"
            ];
        } else {
            $contentParams["software"]["status"][] = [
                "icon" => "pending",
                "title" => "en attente"
            ];
        }
        if ($software["postIsBanned"]) {
            $contentParams["software"]["status"][] = [
                "icon" => "banned",
                "title" => "banni"
            ];
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->viewBuilder->renderTextHTML("Ancres Logicielles", "posts/showSoftware", $contentParams));

        return $this->response;
    }
}