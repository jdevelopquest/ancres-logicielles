<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Request;
use App\Application\Response;
use App\Application\Utils\SessionManager;
use App\Models\AccountModel;
use Exception;

class AccountsController extends Controller
{
    use SessionManager;

    // todo : finir la fonction !!!
    public function signup(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Inscription");

        $this->setPartConfig("content", "account/signup", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    /**
     * Handles the user login process, verifying credentials and initializing the session
     * if the login is successful. Displays appropriate notifications and feedback for login errors
     * or account issues such as bans.
     *
     * @return Response The HTTP response containing the rendered login page or the appropriate error page.
     */
    public function login(): Response
    {
        $contentParams = [];
        $notificationParams = [];

        if ($this->request->isPost()) {
            $username = $this->request->getParams()["accountUsername"] ?? "";
            $password = $this->request->getParams()["accountPassword"] ?? "";

            $accountModel = new AccountModel();

            try {
                $account = $accountModel->loginWithPassword($username, $password);
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503();
            }

            if (empty($account)) {
                $notificationParams["error"] = "Pseudo ou Mot de passe incorrect.";
                $this->escapeHtmlRecursive($username);
                $contentParams["accountUsername"] = $username;
            } else {
                if ($account["accountIsBanned"]) {
                    $notificationParams["error"] = "Compte bannis.";
                } else {
                    $notificationParams["success"] = "Connexion réussie.";

                    $contentParams["success"] = true;

                    $this->escapeHtmlRecursive($account);

                    $this->setupUserSession($account);
                }
            }
        }

        $this->setPageParam("title", "Ancres Logicielles : Connexion");

        $this->setPartConfig("notification", "layouts/notification", $notificationParams, "content");
        $this->setPartConfig("content", "accounts/login", $contentParams, "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

    /**
     * Logs out the current user and handles the logout process.
     * If the request is a POST, performs the user logout and redirects to another page.
     * Otherwise, sets up the page parameters and returns an HTML response.
     *
     * @return Response The response object containing either a redirection or rendered HTML page.
     */
    public function logout(): Response
    {
        if ($this->request->isPost()) {
            $this->userLogout();

            // todo : rediriger vers la page d'accueil après déconnexion
            $postsController = new PostsController(new Request(), new Response());
            return $postsController->indexSoftwares();
        }

        $this->setPageParam("title", "Ancres Logicielles : Déconnexion");

        $this->setPartConfig("content", "accounts/logout", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }
}