<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/koneksi.php';


use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\BookController;
use App\Controllers\MemberController;

$homeController = new HomeController($conn);
$authController = new AuthController($conn);
$bookController = new BookController($conn);
$memberController = new MemberController($conn);


function authMiddleware($callback)
{
    return function () use ($callback) {
        session_start();
        if (!isset($_SESSION['isLoggedIn']) || !isset($_SESSION['username'])) {
            header('Location: /admin/login');
            exit;
        }
        return call_user_func($callback);
    };
}

$routes = [
    '/' => [
        'GET' => [$homeController, 'index'],
    ],

    '/book' => [
        'GET' => [$homeController, 'bookSection']
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
        'GET' => authMiddleware(function () {
            include __DIR__ . '/views/admin/dashboard.php';
        }),
    ],

    '/admin/books' => [
        'GET' => authMiddleware([$bookController, 'index']),
    ],
    '/admin/books/store' => [
        'POST' => authMiddleware([$bookController, 'store']),
    ],
    '/admin/books/update' => [
        'POST' => authMiddleware([$bookController, 'update']),
    ],
    '/admin/books/delete' => [
        'POST' => authMiddleware([$bookController, 'destroy']),
    ],

    '/admin/members' => [
        'GET' => authMiddleware([$memberController, 'index']),
    ],
    '/admin/members/store' => [
        'POST' => authMiddleware([$memberController, 'store']),
    ],
    '/admin/members/update' => [
        'POST' => authMiddleware([$memberController, 'update']),
    ],
    '/admin/members/delete' => [
        'POST' => authMiddleware([$memberController, 'destroy']),
    ],
    
];


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (array_key_exists($requestUri, $routes) && array_key_exists($requestMethod, $routes[$requestUri])) {
    error_log("Route found: " . $requestUri . " [" . $requestMethod . "]");
    call_user_func($routes[$requestUri][$requestMethod]);
    if ($conn) {
        $conn->close(); // Close the connection after handling the request
        $conn = null;
    }
} else {
    error_log("Route not found: " . $requestUri . " [" . $requestMethod . "]");
    header("HTTP/1.0 404 Not Found");
    echo 'Page not found';
    echo '<a href="/">Back</a>';
    if ($conn) {
        $conn->close(); // Close the connection after handling the request
        $conn = null;
    }
}

