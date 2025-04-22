<?php

namespace App\Controllers;

class HomeController
{
    public function index() :void
    {
        require ROOT . '/src/Views/home/index.php';
    }
}