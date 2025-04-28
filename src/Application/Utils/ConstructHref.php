<?php

namespace App\Application\Utils;

trait ConstructHref
{
    protected function constructHref(string $controller, string $action, ?string $id = null): string
    {
        return "index.php?ctr=$controller&act=$action" . (!is_null($id) ? "&id=$id" : "");
    }

}