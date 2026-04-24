<?php

namespace App\Controllers;

use App\Application\Controller;
use App\Application\Response;
use App\Application\Utils\SessionManager;

class SessionsController extends Controller
{
    use SessionManager;

    /**
     * Saves the user's theme based on the data received in the request body.
     *
     * @return Response Returns a JSON response with no content and a 204 HTTP status code.
     */
    public function saveTheme(): Response
    {
        $receive_data = json_decode($this->request->getBody(), true);

        // todo contrôller la valeur du thème
        $this->setUserTheme($receive_data["theme"]);

        return $this->getJsonResponse(null, 204);
    }
}