<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\ConstructHref;
use App\Application\Utils\ConstructMenu;
use App\Application\Utils\LogPrinter;
use App\Models\PostModel;
use Exception;

class PostsController extends Controller
{
    use ConstructHref;
    use ConstructMenu;
    use LogPrinter;

    /**
     * Retrieves and displays a list of software records depending on the user's role.
     * The list may vary depending on whether the user is a guest, registered user, moderator, or admin.
     *
     * Guest users can only access published software, while registered users can access both published and pending software.
     * Moderators and admins have access to all software records.
     *
     * In case of an error during data retrieval, a 503 error response is returned.
     * The retrieved software list is then processed and displayed with appropriate parameters.
     *
     * @return Response The rendered HTML response containing the software list or an error response in case of failure.
     */
    public function indexSoftwares(): Response
    {
        $softwares = [];

        $postModel = new PostModel();

        if ($this->userIsGuest()) {
            try {
                $softwares = $postModel->getSoftwaresPublished();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503();
            }
        }

        if ($this->userIsRegistered()) {
            try {
                $softwares = $postModel->getSoftwaresPublishedAndPending();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503();
            }
        }

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            try {
                $softwares = $postModel->getSoftwares();
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503();
            }
        }

        $contentParams = [];

        if ($softwares) {
            $contentParams["softwares"] = [];

            foreach ($softwares as $software) {
                $this->escapeHtmlRecursive($software);

                $article["href"] = $this->constructHref("posts", "showSoftware", $software["idPost"]);
                $article["softwareName"] = $software["softwareName"];
                $article["status"] = $this->getPostStatusParams($software);

                $contentParams["softwares"][] = $article;
            }
        }

        $this->setPageParam("title", "Ancres Logicielles : Fiches logicielles");

        $this->setPagePartial("content", "posts/indexSoftwares", $contentParams, "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    /**
     * Displays the software details page based on the software ID provided.
     *
     * @param string $idPostSoftware The unique identifier of the software post to display.
     * @return Response The HTTP response containing the rendered HTML page for the software details
     *                  or an error response in case of access restrictions or internal issues.
     */
    public function showSoftware(string $idPostSoftware): Response
    {
        $postModel = new PostModel();

        try {
            $software = $postModel->getSoftwareByIdPost($idPostSoftware);

            // idPost est inexistant
            if (!$software) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error404();
            }
        } catch (Exception $e) {
            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503();
        }

        if ($this->userIsGuest() && !$software["postIsPublished"]) {
            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error403();
        }

        if ($this->userIsRegistered() && $software["postIsBanned"]) {
            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error403();
        }

        $this->escapeHtmlRecursive($software);

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
        $notificationParams = [];

        if ($this->userIsGuest()) {
            try {
                $anchors = $postModel->getPublishedAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notificationParams["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if ($this->userIsRegistered()) {
            try {
                $anchors = $postModel->getPublishedAndPendingAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notificationParams["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if ($this->userIsModerator() || $this->userIsAdmin()) {
            try {
                $anchors = $postModel->getAnchorsByIdPostSoftware($idPostSoftware);
            } catch (Exception $e) {
                $notificationParams["error"] = "Impossible de récupérer les ancres associées.";
            }
        }

        if (!empty($anchors)) {
            $contentParams["anchors"] = [];

            foreach ($anchors as $anchor) {
                $this->escapeHtmlRecursive($anchor);

                $article = [];
                $article["idPost"] = $anchor["idPost"];
                $article["href"] = $this->constructHref("posts", "showAnchor", $anchor["idPost"]);
                $article["anchorUrl"] = $anchor["anchorUrl"];
                $article["anchorContent"] = $anchor["anchorContent"];
                $article["status"] = $this->getPostStatusParams($anchor);

                $contentParams["anchors"][] = $article;
            }
        }

        $this->setPageParam("title", "Ancres Logicielles : " . $software["softwareName"] ?? "Nom du logiciel inconnu");

        $this->setPagePartial("content", "posts/showSoftware", $contentParams, "page");

        $this->setPagePartial("notification", "layouts/notification", $notificationParams, "content");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    /**
     * Unpublishes a post by triggering the appropriate moderation action.
     *
     * @return Response A Response object with no content (empty body) with only an HTTP code
     *  indicating the success (204 No Content) or failure (503 Service Unavailable) of the operation.
     */
    public function unpublish(): Response
    {
        return $this->postModAction("unpublishPost");
    }

    /**
     * Publishes a post by triggering the appropriate moderation action.
     *
     * @return Response A Response object with no content (empty body) with only an HTTP code
     *  indicating the success (204 No Content) or failure (503 Service Unavailable) of the operation.
     */
    public function publish(): Response
    {
        return $this->postModAction("publishPost");
    }

    /**
     * Bans a post by performing a moderation action.
     *
     * @return Response A Response object with no content (empty body) with only an HTTP code
     *  indicating the success (204 No Content) or failure (503 Service Unavailable) of the operation.
     */
    public function ban(): Response
    {
        return $this->postModAction("banPost");
    }

    /**
     * Unbans a post by performing a moderation action.
     *
     * @return Response A Response object with no content (empty body) with only an HTTP code
     *  indicating the success (204 No Content) or failure (503 Service Unavailable) of the operation.
     */
    public function unban(): Response
    {
        return $this->postModAction("unbanPost");
    }

    /**
     * Executes the specified action on a post and returns a response indicating the status of the operation.
     *
     * @param string $action The name of the action to execute on the post.
     * @return Response A Response object with no content (empty body) with only an HTTP code
     * indicating the success (204 No Content) or failure (503 Service Unavailable) of the operation.
     */
    private function postModAction(string $action): Response
    {
        // execute l'action demandée, renvois une réponse du status de l'action et sans contenu

        $receive_data = json_decode($this->request->getBody(), true);

        // todo tester si les données envoyées contiennent les bonnes informations
        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        if (!method_exists($postModel, $action)) {
            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        try {
            $result = $postModel->$action($idPost);

            if (!$result) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        return $this->getJsonResponse(null, 204);
    }

    /**
     * Updates a postbox moderation tool by analyzing the given data, processing the request,
     * rendering the corresponding HTML part, and returning a JSON response.
     *
     * @return Response The response object containing the rendered HTML part or an error status,
     * indicating the operation's success or failure.
     */
    public function updatePostboxModTool(): Response
    {
        $receive_data = json_decode($this->request->getBody(), true);

        // todo tester si les données envoyées contiennent les bonnes informations
        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        try {
            $postStatus = $postModel->getPostStatus($idPost);

            if (empty($postStatus)) {
                $this->logMessage("updatePostboxModTool idPost doit être inexistant");
                $this->logData($idPost);

                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $this->logMessage("updatePostboxModTool problème avec la bd");
            $this->logData($idPost);

            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        $idPost = htmlspecialchars($idPost);
        $this->escapeHtmlRecursive($postStatus);

        $partParams = [];
        $partParams["software"] = [];
        $partParams["software"]["idPost"] = $idPost;
        $partParams["softwareModTools"] = $this->getPostModToolsParams($postStatus);

        $part = $this->renderHtmlPartial("layouts/postbox-mod-tools", $partParams);

        if (empty($part)) {
            $this->logMessage("updatePostboxModTool le rendu est vide");
            $this->logData($idPost);
            $this->logData($partParams);

            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        return $this->getJsonResponse($part, 200);
    }

    /**
     * Updates the status of a software post based on the provided data.
     *
     * The method retrieves the `idPost` provided in the request body,
     * fetches the post-status using the `PostModel`, and renders an HTML
     * part to update the software status. It handles various error
     * scenarios, including empty or invalid data and rendering failures,
     * and returns an appropriate response.
     *
     * @return Response The JSON response containing the rendered HTML part on success,
     *                  or an error response in case of failure.
     */
    public function updateSoftwareStatus(): Response
    {
        $receive_data = json_decode($this->request->getBody(), true);

        $idPost = $receive_data["idPost"];

        $postModel = new PostModel();

        try {
            $postStatus = $postModel->getPostStatus($idPost);

            if (empty($postStatus)) {
                $this->logMessage("updateSoftwareStatus idPost doit être inexistant");
                $this->logData($idPost);

                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503ByAjax();
            }
        } catch (Exception $e) {
            $this->logMessage("updateSoftwareStatus problème avec la bdd");
            $this->logData($idPost);

            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        $idPost = htmlspecialchars($idPost);
        $this->escapeHtmlRecursive($postStatus);

        $part = $this->renderHtmlPartial("layouts/postbox-status", ["postStatus" => $this->getPostStatusParams($postStatus)]);

        if (empty($part)) {
            $this->logMessage("updateSoftwareStatus le rendu est vide");
            $this->logData($idPost);
            $this->logData($postStatus);

            $errorsController = new ErrorsController($this->request, $this->response);
            return $errorsController->error503ByAjax();
        }

        return $this->getJsonResponse($part, 200);
    }

    /**
     * Determines the status parameters for a post based on its properties.
     *
     * The method evaluates the post's attributes to construct an array of
     * status information, including an icon and title that represent the
     * post's current state (e.g., published, banned, or pending).
     *
     * @param array $post An associative array containing the post's data, including
     *                    its publication, ban status, and other necessary attributes.
     * @return array An array of status details, each element containing information
     *               such as the status icon and corresponding title.
     */
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

    /**
     * Generates a list of moderation tool parameters based on the status of a post.
     *
     * The method evaluates the input post-data and determines the moderation
     * actions that can be performed, such as banning or publishing the post.
     * It returns the list of tools with corresponding actions, icons, and titles.
     *
     * @param mixed $post The data of the post, containing its current status and properties.
     *
     * @return array The list of moderation tools, each represented as an associative array
     *               containing the action, icon, and title.
     */
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