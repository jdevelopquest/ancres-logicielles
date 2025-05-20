<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Request;
use App\Application\Response;
use App\Application\Utils\ConstructMenu;
use App\Application\Utils\SessionManager;
use App\Models\AccountModel;
use Exception;

class AccountsController extends Controller
{
    use SessionManager;
    use ConstructMenu;

    // todo : finir la fonction !!!
    public function signup(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Inscription");

        $this->setPartConfig("content", "account/signup", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }

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
                $errorsController = new ErrorsController($this->request);
                return $errorsController->error503();
            }

            if (empty($account)) {
                $notificationParams["error"] = "Pseudo ou Mot de passe incorrect.";
                $contentParams["accountUsername"] = htmlspecialchars($username);
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

    public function logout(): Response
    {
        if ($this->request->isPost()) {
            $this->userLogout();

            $postsController = new PostsController(new Request());
            return $postsController->indexSoftwares();
        }

        $this->setPageParam("title", "Ancres Logicielles : Déconnexion");

        $this->setPartConfig("content", "accounts/logout", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage());
    }
}