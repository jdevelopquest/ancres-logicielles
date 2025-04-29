<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\SessionManager;

class UsersApiController extends Controller
{
    use SessionManager;
    public function saveTheme(): Response
    {
        $this->response->setHeaders([
            "Content-Type: application/json",
        ]);

        $receive_data = json_decode($this->request->getBody(), true);

        // todo
        // contrôller la valeur du thème
        $this->setUserTheme($receive_data["theme"]);

        $this->response->setCode(204);

        return $this->response;
    }
}