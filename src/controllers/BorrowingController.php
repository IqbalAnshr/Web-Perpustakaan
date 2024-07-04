<?php

namespace App\Controllers;

use App\Models\BorrowingModel;

class BorrowingController
{
    private $conn;
    private $borrowingModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->borrowingModel = new BorrowingModel($conn);
    }
    public function index()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ISBN_Buku';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;

        $queryString = http_build_query([
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
        ]);

        $borrowings = $this->borrowingModel->getAllBorrowings($search, $sort, $order, $page, $limit);
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $raks = $this->conn->query("SELECT ISBN, Status_Pinjam, Jumlah_Tersedia FROM Buku")->fetch_all(MYSQLI_ASSOC);
        $members = $this->conn->query("SELECT NIM, Nama, Alamat FROM Anggota")->fetch_all(MYSQLI_ASSOC);
        $totalBorrowings = $this->borrowingModel->getTotalBorrowings($search);
        $totalPages = ceil($totalBorrowings / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        include __DIR__ . '/../views/admin/borrowings/index.php';
    }

    public function store()
    {
        $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
        $nim_anggota = $_POST['nim_anggota'];
        $isbn_buku = $_POST['isbn_buku'];

        if (empty($tanggal_peminjaman) || empty($tanggal_pengembalian) || empty($nim_anggota) || empty($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Harap lengkapi semua kolom";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $result = $this->borrowingModel->insertBorrowing($tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Peminjaman ditambahkan" : "Terjadi kesalahan: " . $this->conn->error;

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $id_peminjaman = $_POST['id_peminjaman'];
        $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
        $nim_anggota = $_POST['nim_anggota'];
        $isbn_buku = $_POST['isbn_buku'];

        $result = $this->borrowingModel->updateBorrowing($id_peminjaman, $tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku);

        $_SESSION['success'] = $result !== false;
        $_SESSION['message'] = $result !== false ? "Peminjaman diubah" : "Terjadi kesalahan: " . $this->conn->error;

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function destroy()
    {
        if ($_POST['_method'] !== 'DELETE') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $id_peminjaman = $_POST['id_peminjaman'];

        $result = $this->borrowingModel->deleteBorrowing($id_peminjaman);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Peminjaman dihapus" : "Terjadi kesalahan: " . $this->conn->error;

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
