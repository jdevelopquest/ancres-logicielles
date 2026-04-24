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

    /**
     * Handles the signup process for a user, validating user input, checking for errors,
     * and attempting to create a new account if the data is valid.
     *
     * Validates the username and password formats, checks if the username is already in use,
     * and registers the account if no validation errors are found. On successful registration,
     * a success notification is set. Otherwise, appropriate error messages are set in the notification.
     *
     * In case of a server error during processing, a 503 error response is returned.
     *
     * @return Response A Response object containing the rendered HTML page for the signup process
     *                  or an error response in case of server-side failures.
     */
    public function signup(): Response
    {
        $contentParams = [];
        $notificationParams = [];

        if ($this->request->isPost()) {
            $username = $this->request->getParams()["accountUsername"] ?? "";
            $password = $this->request->getParams()["accountPassword"] ?? "";

            $accountModel = new AccountModel();

            if (!$accountModel->isUsernameMatchPattern($username)) {
                $notificationParams["error"] = [];
                $notificationParams["error"][] = "Le pseudo doit contenir entre 3 et 200 caractères, et ne doit pas contenir de caractères spéciaux.";
                $this->escapeHtmlRecursive($username);
                $contentParams["accountUsername"] = $username;
            }

            if (!$accountModel->isPasswordMatchPattern($password)) {
                if (!isset($notificationParams["error"])) {
                    $notificationParams["error"] = [];
                }
                $notificationParams["error"][] = "Le mot de passe doit contenir au minimum 12 caractères, et ne doit pas contenir de caractères spéciaux.";
                $notificationParams["error"][] = "Le mot de passe doit contenir au minimum 1 minuscule, 1 majuscule, 1 chiffre.";
            }

            try {
                if (!isset($notificationParams["error"])) {
                    if ($accountModel->isUsernameExists($username)) {
                        $notificationParams["error"] = [];
                        $notificationParams["error"][] = "Ce pseudo est déjà utilisé.";
                    } else {
                        $success = $accountModel->registerAccount($username, $password);

                        if ($success) {
                            $notificationParams["success"] = [];
                            $notificationParams["success"][] = "Inscription réussie.";
                            $contentParams["signup_success"] = true;
                        } else {
                            $notificationParams["error"] = [];
                            $notificationParams["error"][] = "Une erreur est survenue lors de l'inscription.";
                            $contentParams["signup_success"] = false;
                        }
                    }
                }
            } catch (Exception $e) {
                $errorsController = new ErrorsController($this->request, $this->response);
                return $errorsController->error503();
            }
        }

        $this->setPageParam("title", "Ancres Logicielles : Inscription");

        $this->setPartConfig("notification", "layouts/notification", $notificationParams, "content");
        $this->setPartConfig("content", "accounts/signup", $contentParams, "page");

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
                $notificationParams["error"] = [];
                $notificationParams["error"][] = "Pseudo ou Mot de passe incorrect.";
                $this->escapeHtmlRecursive($username);
                $contentParams["accountUsername"] = $username;
            } else if ($account["accountIsBanned"]) {
                $notificationParams["error"] = [];
                $notificationParams["error"][] = "Compte bannis.";
            } else if ($account["accountIsSuspended"]) {
                $notificationParams["error"] = [];
                $notificationParams["error"][] = "Compte suspendu.";
            }  else {
                $notificationParams["success"] = [];
                $notificationParams["success"][] = "Connexion réussie.";

                $contentParams["login_success"] = true;

                $this->escapeHtmlRecursive($account);

                $this->setupUserSession($account);
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