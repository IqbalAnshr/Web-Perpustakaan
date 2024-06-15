<?php

namespace App\Controllers;


class AuthController
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function Register()
    {
        include __DIR__ . '/../views/admin/register.php';
    }

    public function handleRegister()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
        }


        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header('Location: /admin/register?error=empty');
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO User (username, password, role) VALUES ('$username', '$hashed_password', 'admin')";
        $result = $this->conn->query($query);

        if ($result) {
            header('Location: /admin/login');
            exit;
        } else {
            header('Location: /admin/register?error=sqlerror');
            exit;
        }
    }


    public function login()
    {
        include __DIR__ . '/../views/admin/login.php';
    }

    public function handleLogin()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            echo 'Method not allowed';
            exit;
        }


        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';


        if (empty($username) || empty($password)) {
            header('Location: /admin/login?error=empty');
            exit;
        }

        // Hindari SQL Injection
        $username = mysqli_real_escape_string($this->conn, $username);

        $query = "SELECT * FROM User WHERE username = '$username' AND role = 'admin' LIMIT 1";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashed_password_from_db = $user['Password'];

            if (password_verify($password, $hashed_password_from_db)) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['isLoggedIn'] = true;

                header('Location: /admin/dashboard');
                exit;
            } else {
                header('Location: /admin/login?error=incorrect');
                exit;
            }
        } else {
            header('Location: /admin/login?error=incorrect');
            exit;
        }
    }



    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /admin/login');
        exit;
    }


}