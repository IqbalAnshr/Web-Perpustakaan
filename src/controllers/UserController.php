<?php

namespace App\controllers;

use App\models\UserModel;

class UserController
{

    private $conn;

    private $userModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
    }

    public function index()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ID_User';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;

        $queryString = http_build_query([
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
            'order' => $order,
        ]);

        // Mengambil data anggota dan total anggota
        $users = $this->userModel->getAllUsers($search, $sort, $order, $page, $limit, $filter);
        $totalUsers = $this->userModel->getTotalUsers($search, $filter);
        $totalPages = ceil($totalUsers / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        // Menyertakan file view
        include __DIR__ . '/../views/admin/users/index.php';
    }

    public function store()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (empty($username) || empty($password) || empty($role)) {
            $success = false;
            $message = "Harap lengkapi semua kolom";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $result = $this->userModel->insertUser($username, $passwordHash, $role);
        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "User ditambahkan" : "Terjadi kesalahan: " . $this->conn->error;

        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        //refresh halaman
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        // Ambil data dari POST request
        $id_user = $_POST['id_user'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (empty($username) || empty($role)) {
            $success = false;
            $message = "Harap lengkapi semua kolom";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);


        // Update anggota
        $result = $this->userModel->updateUser($username, $passwordHash, $role, $id_user);
        if ($result === FALSE) {
            $message = "Terjadi kesalahan: " . $this->conn->error;
            $success = false;
        } else {
            $message = "User diubah";
            $success = true;
        }

        // Set session messages
        $_SESSION['success'] = $success;
        $_SESSION['message'] = $message;

        // Redirect ke halaman sebelumnya
        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        exit;
    }

    public function destroy()
    {
        if ($_POST['_method'] !== 'DELETE') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }
        // Ambil nilai NIM dari form
        $id_user = $_POST['id_user'];
        $result = $this->userModel->deleteUser($id_user);
        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "User dihapus" : "Terjadi kesalahan: " . $this->conn->error;

        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        exit;
    }
}
