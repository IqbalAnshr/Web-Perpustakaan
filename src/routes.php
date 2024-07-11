<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/koneksi.php';


use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\BookController;
use App\Controllers\MemberController;
use App\Controllers\ReturnController;
use App\Controllers\BorrowingController;
use App\Controllers\ShelvesController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;

$homeController = new HomeController($conn);
$authController = new AuthController($conn);
$bookController = new BookController($conn);
$returnController = new ReturnController($conn);
$memberController = new MemberController($conn);
$borrowingController = new BorrowingController($conn);
$shelvesController = new ShelvesController($conn);
$dashboardController = new DashboardController($conn);
$userController = new UserController($conn);


function authMiddleware($callback)
{
    return function () use ($callback) {
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

    '/book/detail' => [
        'GET' => [$homeController, 'detailBook']
    ],

    '/book/borrowings/create' => [
        'GET' => [$homeController, 'borrowingForm'],
    ],
    '/book/borrowings/request-verification' => [
        'POST' => [$homeController, 'requestVerification'],
    ],
    '/book/borrowings/processBorrowing' => [
        'POST' => [$homeController, 'processBorrowing'],
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
        'GET' => authMiddleware([$dashboardController, 'index']),
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

    '/admin/shelves' => [
        'GET' => authMiddleware([$shelvesController, 'index']),
    ],
    '/admin/shelves/store' => [
        'POST' => authMiddleware([$shelvesController, 'store']),
    ],
    '/admin/shelves/update' => [
        'POST' => authMiddleware([$shelvesController, 'update']),
    ],
    '/admin/shelves/delete' => [
        'POST' => authMiddleware([$shelvesController, 'destroy']),
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
    '/admin/returns' => [
        'GET' => authMiddleware([$returnController, 'index']),
    ],
    '/admin/returns/store' => [
        'POST' => authMiddleware([$returnController, 'add']),
    ],
    '/admin/returns/update' => [
        'POST' => authMiddleware([$returnController, 'edit']),
    ],
    '/admin/returns/delete' => [
        'POST' => authMiddleware([$returnController, 'delete']),
    ],

    '/admin/borrowings' => [
        'GET' => authMiddleware([$borrowingController, 'index']),
    ],
    '/admin/borrowings/store' => [
        'POST' => authMiddleware([$borrowingController, 'store']),
    ],
    '/admin/borrowings/update' => [
        'POST' => authMiddleware([$borrowingController, 'update']),
    ],
    '/admin/borrowings/delete' => [
        'POST' => authMiddleware([$borrowingController, 'destroy']),
    ],

    '/admin/users' => [
        'GET' => authMiddleware([$userController, 'index']),
    ],
    '/admin/users/store' => [
        'POST' => authMiddleware([$userController, 'store']),
    ],
    '/admin/users/update' => [
        'POST' => authMiddleware([$userController, 'update']),
    ],
    '/admin/users/delete' => [
        'POST' => authMiddleware([$userController, 'destroy']),
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

