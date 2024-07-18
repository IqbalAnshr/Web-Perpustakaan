<?php

namespace App\controllers;

use App\models\ShelvesModel;

class ShelvesController
{
    private $conn;
    private $shelvesModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->shelvesModel = new ShelvesModel($conn);
    }

    public function index()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ID_Rak';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;

        $queryString = http_build_query([
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
            'order' => $order,
        ]);

        $shelves = $this->shelvesModel->getAllShelvesWithBookCount($search, $filter, $sort, $order, $page, $limit);
        $totalShelves = $this->shelvesModel->getTotalShelves($search, $filter);
        $totalPages = ceil($totalShelves / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        include __DIR__ . '/../views/admin/shelves/index.php';
    }

    public function store()
    {
        $lokasi = $_POST['lokasi'];
        $kapasitas = $_POST['kapasitas'];
        $kategori = $_POST['kategori'];
        $keterangan = $_POST['keterangan'];

        if (empty($lokasi) || empty($kapasitas) || empty($kategori) || empty($keterangan)) {
            $success = false;
            $message = "Harap lengkapi semua kolom";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }

        $result = $this->shelvesModel->insertShelf($lokasi, $kapasitas, $kategori, $keterangan);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Rak ditambahkan" : "Terjadi kesalahan: " . $this->conn->error;

        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $id_rak = $_POST['id_rak'];
        $lokasi = $_POST['lokasi'];
        $kapasitas = $_POST['kapasitas'];
        $kategori = $_POST['kategori'];
        $keterangan = $_POST['keterangan'];

        if (empty($id_rak) || empty($lokasi) || empty($kapasitas) || empty($kategori) || empty($keterangan)) {
            $success = false;
            $message = "Harap lengkapi semua kolom";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }

        $result = $this->shelvesModel->updateShelf($id_rak, $lokasi, $kapasitas, $kategori, $keterangan);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Rak diubah" : "Terjadi kesalahan: " . $this->conn->error;

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

        $id_rak = $_POST['id_rak'];

        if (empty($id_rak)) {
            $success = false;
            $message = "ID Rak tidak ditemukan";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }

        $result = $this->shelvesModel->deleteShelf($id_rak);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Rak dihapus" : "Terjadi kesalahan: " . $this->conn->error;

        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        exit;
    }
}

