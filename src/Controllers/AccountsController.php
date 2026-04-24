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

    public function signup(): Response
    {
        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Inscription", "accounts/signup"));

        return $this->response;
    }

    public function login(): Response
    {
        $contentParams = [];
        $notification = [];

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
                $notification["error"] = "Pseudo ou Mot de passe incorrect.";
                $contentParams["accountUsername"] = htmlspecialchars($username);
            } else {
                if ($account["accountIsBanned"]) {
                    $notification["error"] = "Compte bannis.";
                } else {
                    $notification["success"] = "Connexion réussie.";

                    $contentParams["success"] = true;

                    $this->htmlspecialcharsWalking($account);

                    $this->setupUserSession($account);
                }
            }
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Connexion", "accounts/login", $contentParams, $notification));

        return $this->response;
    }

    public function logout(): Response
    {
        if ($this->request->isPost()) {
            $this->userLogout();

            $postsController = new PostsController(new Request());
            return $postsController->indexSoftwares();
        }

        $this->response->setHeaders([
            "Content-Type" => "text/html",
        ]);

        $this->response->setCode(200);

        $this->response->setBody($this->renderPage("Ancres Logicielles : Déconnexion", "accounts/logout"));

        return $this->response;
    }
}