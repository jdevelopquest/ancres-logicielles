<?php
declare(strict_types=1);

namespace App\Src\Controllers;

use App\Core\Controller;
use App\Core\Response;

class HealthsController extends Controller
{
    public function health(): Response
    {
        $this->setPageParam("title", "Ancres Logicielles : Health Test");

        $this->setViewComponent("content", "healths/health", [], "page");

        return $this->getHtmlResponse($this->renderHtmlPage(), 200);
    }

    public function healthJson(): Response
    {
        return $this->getJsonResponse(["status" => "healthy"], 200);
    }
}
