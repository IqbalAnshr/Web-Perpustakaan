<?php

namespace App\Controllers;

use App\Models\ReturnModel;
use App\Models\BookModel;
use App\Models\BorrowingModel;
use DateTime;


class ReturnController
{
    private $conn;
    private $returnModel;
    private $bookModel;
    private $borrowingModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->returnModel = new ReturnModel($conn);
        $this->bookModel = new BookModel($conn);
        $this->borrowingModel = new BorrowingModel($conn);
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
        $returns = $this->returnModel->getAllReturns($search, $filter, $sort, $order, $page, $limit);
        $totalItems = $this->returnModel->countAllReturns($search, $filter);
        $totalPages = ceil($totalItems / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        // Get list of unpaid transactions for the modal
        $unreturnedTransactions = $this->returnModel->getUnreturnedTransactions();

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
        $id_peminjaman = $_POST['id_peminjaman'];
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'];

        // Validasi data input
        if (empty($id_peminjaman) || empty($tanggal_pengembalian)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Harap lengkapi semua kolom";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Ambil tanggal peminjaman dan tanggal pengembalian dari database
        $dates = $this->returnModel->getBorrowingDates($id_peminjaman);
        $tanggal_pengembalian_seharusnya = $dates['Tanggal_Jatuh_Tempo'];

        // Hitung keterlambatan
        $tanggal_pengembalian_seharusnya_obj = new DateTime($tanggal_pengembalian_seharusnya);
        $tanggal_pengembalian_obj = new DateTime($tanggal_pengembalian);
        $interval = $tanggal_pengembalian_obj->diff($tanggal_pengembalian_seharusnya_obj);
        $denda = 0;

        // Jika tanggal pengembalian lebih dari tanggal jatuh tempo, hitung denda
        if ($tanggal_pengembalian_obj > $tanggal_pengembalian_seharusnya_obj) {
            $jumlah_hari_telat = $interval->days;
            $denda = $jumlah_hari_telat * 3000;
        }

        // Tambahkan pengembalian
        $result = $this->returnModel->addReturn($id_peminjaman, $tanggal_pengembalian, $denda);

        if ($result) {
            // Ambil ISBN Buku dari peminjaman
            $isbn_buku = $this->borrowingModel->getIsbnByBorrowingId($id_peminjaman);

            // Tambahkan jumlah buku yang tersedia
            $this->bookModel->increaseAvailableQuantity($isbn_buku);

            // Tandai peminjaman sebagai sudah dikembalikan
            $this->borrowingModel->markAsReturned($id_peminjaman);

            $_SESSION['success'] = true;
            $_SESSION['message'] = "Pengembalian ditambahkan";
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan: " . $this->conn->error;
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }


    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengembalian = $_POST['id_pengembalian'];
            $id_peminjaman = $_POST['id_peminjaman'];
            $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
            $denda = $_POST['denda'];

            $this->returnModel->editReturn($id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda);

            session_start();
            $_SESSION['message'] = "Data pengembalian berhasil diubah.";
            $_SESSION['success'] = true;

            header('Location: /admin/returns');
            exit;
        }
    }

    public function delete()
    {
        if ($_POST['_method'] !== 'DELETE') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $id_pengembalian = $_POST['id_pengembalian'];

        // Ambil ID peminjaman dari ID pengembalian
        $id_peminjaman = $this->returnModel->getBorrowingIDByReturnID($id_pengembalian);

        if (!$id_peminjaman) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Peminjaman tidak ditemukan.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Tandai peminjaman sebagai belum dikembalikan
        if (!$this->borrowingModel->markAsNotReturned($id_peminjaman)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Gagal menandai peminjaman $id_peminjaman sebagai belum dikembalikan.";
            error_log("Gagal menandai peminjaman $id_peminjaman sebagai belum dikembalikan: " . $this->conn->error);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Kurangi jumlah buku yang tersedia
        $isbn_buku = $this->borrowingModel->getIsbnByBorrowingId($id_peminjaman);



        if (!$this->bookModel->decreaseAvailableQuantity($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan saat mengurangi jumlah buku.";
            error_log("Gagal mengurangi jumlah buku $isbn_buku");
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Hapus data pengembalian
        if (!$this->returnModel->deleteReturn($id_pengembalian)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan saat menghapus data pengembalian: " . $this->conn->error;
            error_log("Kesalahan saat menghapus data pengembalian: " . $this->conn->error);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Set pesan sukses
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Data pengembalian berhasil dihapus.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
