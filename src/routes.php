<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;

$homeController = new HomeController();

if ($_SERVER['REQUEST_URI'] === '/') {
    $homeController->index();
}else {
    header("HTTP/1.0 404 Not Found");
    echo 'Page not found';
}