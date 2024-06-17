<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/koneksi.php';


use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\BookController;

$homeController = new HomeController($conn);
$authController = new AuthController($conn);
$bookController = new BookController($conn);


$routes = [
    '/' => [
        'GET' => [$homeController, 'index'],
    ],

    '/admin/register' => [
        'GET' => [$authController, 'register'],
        'POST' => [$authController, 'handleRegister'],  
    ],
    '/admin/login' => [
        'GET' => [$authController, 'login'],
        'POST' => [$authController, 'handleLogin'],
    ],
    '/admin/logout' => [
        'GET' => [$authController, 'logout'],
    ],
    '/admin/dashboard' => [
        'GET' => function () {
            session_start();
            if (!isset($_SESSION['isLoggedIn']) && !isset($_SESSION['username'])) {
                header('Location: /admin/login');
                exit;
            }
            include __DIR__ . '/views/admin/dashboard.php';
        }
    ],

    '/admin/books' => [
        'GET' => [$bookController, 'index'],
    ],
    '/admin/books/store' => [
        'POST' => [$bookController, 'store'],
    ],
    '/admin/books/update' => [
        'POST' => [$bookController, 'update'],
    ],
    '/admin/books/delete' => [
        'POST' => [$bookController, 'destroy'],
    ]
];


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (array_key_exists($requestUri, $routes) && array_key_exists($requestMethod, $routes[$requestUri])) {
    error_log("Route found: " . $requestUri . " [" . $requestMethod . "]");
    call_user_func($routes[$requestUri][$requestMethod]);
} else {
    error_log("Route not found: " . $requestUri . " [" . $requestMethod . "]");
    header("HTTP/1.0 404 Not Found");
    echo 'Page not found';
    echo '<a href="/">Back</a>';
}

