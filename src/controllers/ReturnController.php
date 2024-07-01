<?php

namespace App\Controllers;

use App\Models\ReturnModel;

class ReturnController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new ReturnModel($conn);
    }

    public function index()
    {
        // Pagination logic
        $perPage = 10; // Items per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalItems = $this->model->countAllReturns();
        $totalPages = ceil($totalItems / $perPage);
        $previousPage = $page > 1 ? $page - 1 : null;
        $nextPage = $page < $totalPages ? $page + 1 : null;

        $returns = $this->model->getReturnsByPage($page, $perPage);

        // Pass variables to the view
        include __DIR__ . '/../views/admin/returns/index.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengembalian = $_POST['id_pengembalian'];
            $id_peminjaman = $_POST['id_peminjaman'];
            $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
            $denda = $_POST['denda'];

            $this->model->addReturn($id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda);

            session_start();
            $_SESSION['message'] = "Data pengembalian berhasil ditambahkan.";
            $_SESSION['success'] = true;

            header('Location: /admin/returns');
            exit;
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengembalian = $_POST['id_pengembalian'];
            $id_peminjaman = $_POST['id_peminjaman'];
            $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
            $denda = $_POST['denda'];

            $this->model->editReturn($id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda);

            session_start();
            $_SESSION['message'] = "Data pengembalian berhasil diubah.";
            $_SESSION['success'] = true;

            header('Location: /admin/returns');
            exit;
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengembalian = $_POST['id_pengembalian'];

            $this->model->deleteReturn($id_pengembalian);

            session_start();
            $_SESSION['message'] = "Data pengembalian berhasil dihapus.";
            $_SESSION['success'] = true;

            header('Location: /admin/returns');
            exit;
        }
    }
}
