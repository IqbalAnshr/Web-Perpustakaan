<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        require_once __DIR__ . '/../views/home.php';
    }
}
