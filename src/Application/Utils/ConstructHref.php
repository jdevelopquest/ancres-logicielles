<?php
declare(strict_types=1);

namespace App\Application\Utils;

trait ConstructHref
{
    protected function constructHref(string $controller, string $action, ?int $id = null): string
    {
        return "/$controller/$action" . (!is_null($id) ? "?id=$id" : "");
    }
}