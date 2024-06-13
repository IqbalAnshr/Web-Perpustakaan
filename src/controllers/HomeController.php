<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/koneksi.php';

class HomeController
{
    public function index()
    {
        require_once __DIR__ . '/../views/home.php';
    }

    public function teskoneksi()
    {
       
    }
}
