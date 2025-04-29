<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\ConstructHref;
use App\Application\Utils\LogPrinter;
use App\Models\PostModel;
use Exception;

class PostsController extends Controller
{
    use ConstructHref;
    use LogPrinter;

    public function indexSoftwares(): Response
    {
        $softwares = [];

        $postModel = new PostModel();

        if ($this->userIsGuest()) {
            try {
                $softwares = $postModel->getSoftwaresPublished();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        if ($this->userIsRegistered()) {
            try {
                $softwares = $postModel->getSoftwaresPublishedAndPending();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            try {
                $softwares = $postModel->getSoftwares();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }
        }

        $contentParams = [];

        if ($softwares) {
            $contentParams["softwares"] = [];

            foreach ($softwares as $software) {
                $this->htmlspecialcharsWalking($software);

                $article["href"] = $this->constructHref("posts", "showSoftware", $software["idPost"]);
                $article["softwareName"] = $software["softwareName"];
                $article["status"] = $this->getPostStatusParams($software);

                $contentParams["softwares"][] = $article;
            }
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->renderPage("Ancres Logicielles", "posts/indexSoftwares", $contentParams));

        return $this->response;
    }

    public function showSoftware(string $idPostSoftware): Response
    {
        $postModel = new PostModel();

        try {
            $software = $postModel->getSoftwareByIdPost($idPostSoftware);

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

        $this->htmlspecialcharsWalking($software);

        $contentParams = [];
        $contentParams["software"] = [];
        $contentParams["software"]["idPost"] = $software["idPost"];
        $contentParams["software"]["softwareName"] = $software["softwareName"];

        $contentParams["software"]["summary"] = [];
        foreach (explode("\n", $software["softwareSummary"]) as $summaryPart) {
            $contentParams["software"]["summary"][] = $summaryPart;
        }

        $contentParams["software"]["status"] = $this->getPostStatusParams($software);

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            $contentParams["softwareModTools"] = $this->getPostModToolsParams($software);
        }

        // récupérer les liens associés à la fiche
        $anchors = [];
        $notification = [];

        if ($this->userIsGuest()) {
            try {
                $anchors = $postModel->getPublishedAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notification["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if ($this->userIsRegistered()) {
            try {
                $anchors = $postModel->getPublishedAndPendingAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notification["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            try {
                $anchors = $postModel->getAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notification["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if (!empty($anchors)) {
            $contentParams["anchors"] = [];

            foreach ($anchors as $anchor) {
                $this->htmlspecialcharsWalking($anchor);

                $article = [];
                $article["idPost"] = $anchor["idPost"];
                $article["href"] = $this->constructHref("posts", "showAnchor", $anchor["idPost"]);
                $article["anchorUrl"] = $anchor["anchorUrl"];
                $article["anchorContent"] = $anchor["anchorContent"];
                $article["status"] = $this->getPostStatusParams($anchor);

                $contentParams["anchors"][] = $article;
            }
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $title = $software["softwareName"];

        $this->response->setBody($this->renderPage("Ancres Logicielles : $title", "posts/showSoftware", $contentParams, $notification));

        return $this->response;
    }

    public function unpublish(): Response
    {
        return $this->postModAction("unpublishPost");
    }

    public function publish(): Response
    {
        return $this->postModAction("publishPost");
    }

    public function ban(): Response
    {
        return $this->postModAction("banPost");
    }

    public function unban(): Response
    {
        return $this->postModAction("unbanPost");
    }

    private function postModAction(string $action): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $receive_data = json_decode($this->request->getBody(), true);

        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        if (!method_exists($postModel, $action)) {
            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        try {
            $result = $postModel->$action($idPost);

            if (!$result) {
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        $this->response->setCode(204);

        return $this->response;
    }

    public function updatePostboxModTool(): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $receive_data = json_decode($this->request->getBody(), true);

        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        try {
            $postStatus = $postModel->getPostStatus($idPost);

            if (empty($postStatus)) {
                $this->logMessage("updatePostboxModTool idPost doit être inexistant");
                $this->logData($idPost);

                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $this->logMessage("updatePostboxModTool problème avec la bd");
            $this->logData($idPost);

            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        $idPost = htmlspecialchars($idPost);
        $this->htmlspecialcharsWalking($postStatus);

        $partParams = [];
        $partParams["software"] = [];
        $partParams["software"]["idPost"] = $idPost;
        $partParams["softwareModTools"] = $this->getPostModToolsParams($postStatus);

        $part = $this->renderPart("layouts/postbox-mod-tools", $partParams);

        if (empty($part)) {
            $this->logMessage("updatePostboxModTool le rendu est vide");
            $this->logData($idPost);
            $this->logData($partParams);

            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        $this->response->setCode(200);

        $this->response->setBody(json_encode($part));

        return $this->response;
    }

    public function updateSoftwareStatus(): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $receive_data = json_decode($this->request->getBody(), true);

        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        try {
            $postStatus = $postModel->getPostStatus($idPost);

            if (empty($postStatus)) {
                $this->logMessage("updateSoftwareStatus idPost doit être inexistant");
                $this->logData($idPost);

                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $this->logMessage("updateSoftwareStatus problème avec la bd");
            $this->logData($idPost);

            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        $idPost = htmlspecialchars($idPost);
        $this->htmlspecialcharsWalking($postStatus);

        $partParams = [];
        $partParams["software"] = [];
        $partParams["software"]["idPost"] = $idPost;
        $partParams["software"]["status"] = $this->getPostStatusParams($postStatus);

        $part = $this->renderPart("layouts/postbox-status", $partParams);

        if (empty($part)) {
            $this->logMessage("updateSoftwareStatus le rendu est vide");
            $this->logData($idPost);
            $this->logData($partParams);

            $errorsController = new ErrorsController($this->request);
            return $errorsController->error503ByAjax();
        }

        $this->response->setCode(200);

        $this->response->setBody(json_encode($part));

        return $this->response;

    }

    // utils
    private function getPostStatusParams(array $post): array
    {
        $status = [];

        if ($post["postIsPublished"]) {
            $status[] = [
                "icon" => "post-published",
                "title" => "publiée"
            ];
        } else if ($post["postIsBanned"]) {
            $status[] = [
                "icon" => "post-banned",
                "title" => "banni"
            ];
        } else {
            $status[] = [
                "icon" => "post-pending",
                "title" => "en attente"
            ];
        }

        return $status;
    }

    private function getPostModToolsParams(mixed $post): array
    {
        $tools = [];

        if ($post["postIsBanned"]) {
            $tools[] = [
                "action" => "unban",
                "icon" => "post-unban",
                "title" => "Rétirer le bannissement"
            ];

            return $tools;
        }

        if ($post["postIsPublished"]) {
            $tools[] = [
                "action" => "unpublish",
                "icon" => "post-unpublish",
                "title" => "Rétirer la publication"
            ];
        } else {
            $tools[] = [
                "action" => "publish",
                "icon" => "post-publish",
                "title" => "Mettre en publication"
            ];
        }

        $tools[] = [
            "action" => "ban",
            "icon" => "post-ban",
            "title" => "Mettre en bannissement"
        ];

        return $tools;
    }
}