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
        // Get search, filter, sort, and pagination parameters from the query string
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Tanggal_Pengembalian'; // Default sort column
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
        $limit = 10; // Items per page

        // Fetch data from the model
        $returns = $this->model->getAllReturns($search, $filter, $sort, $order, $page, $limit);
        $totalItems = $this->model->countAllReturns($search, $filter);
        $totalPages = ceil($totalItems / $limit);
        $previousPage = $page > 1 ? $page - 1 : null;
        $nextPage = $page < $totalPages ? $page + 1 : null;
        $pages = range(1, $totalPages);

        // Get list of unpaid transactions for the modal
        $unreturnedTransactions = $this->model->getUnreturnedTransactions();

        // Build the query string for pagination links
        $queryString = http_build_query([
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
            'order' => $order,
        ]);

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
