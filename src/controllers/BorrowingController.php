<?php

namespace App\Controllers;

use App\Models\BorrowingModel;
use App\Models\BookModel;

class BorrowingController
{
    private $conn;
    private $borrowingModel;

    private $bookModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->borrowingModel = new BorrowingModel($conn);
        $this->bookModel = new BookModel($conn);
    }
    public function index()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Tanggal_Peminjaman';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;

        $tgl_peminjaman = isset($_GET['tgl_peminjaman']) ? $_GET['tgl_peminjaman'] : '';
        $tgl_pengembalian = isset($_GET['tgl_pengembalian']) ? $_GET['tgl_pengembalian'] : '';

        $queryString = http_build_query([
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_pengembalian' => $tgl_pengembalian,
        ]);

        $borrowings = $this->borrowingModel->getAllBorrowings($search, $tgl_peminjaman, $tgl_pengembalian, $sort, $order, $page, $limit);
        $books = $this->conn->query("SELECT ISBN, Judul FROM Buku")->fetch_all(MYSQLI_ASSOC);
        $members = $this->conn->query("SELECT NIM, Nama, Alamat FROM Anggota")->fetch_all(MYSQLI_ASSOC);
        $totalBorrowings = $this->borrowingModel->getTotalBorrowings($search, $tgl_peminjaman, $tgl_pengembalian);
        $totalPages = ceil($totalBorrowings / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        include __DIR__ . '/../views/admin/borrowings/index.php';
    }




    public function store()
    {
        $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
        $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
        $nim_anggota = $_POST['nim_anggota'];
        $isbn_buku = $_POST['isbn_buku'];

        // Validasi data input
        if (empty($tanggal_peminjaman) || empty($tanggal_jatuh_tempo) || empty($nim_anggota) || empty($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Harap lengkapi semua kolom";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Periksa ketersediaan buku
        if (!$this->bookModel->isBookAvailable($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Buku dengan ISBN $isbn_buku tidak tersedia";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        if (!$this->bookModel->isBookCanBeBorrowed($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Buku dengan ISBN $isbn_buku tidak dapat dipinjam";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Tambahkan peminjaman
        $result = $this->borrowingModel->insertBorrowing($tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku);

        if ($result) {
            // Kurangi jumlah buku yang tersedia
            $this->bookModel->decreaseAvailableQuantity($isbn_buku);

            $_SESSION['success'] = true;
            $_SESSION['message'] = "Peminjaman ditambahkan";
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan: " . $this->conn->error;
        }

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
        $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
        $nim_anggota = $_POST['nim_anggota'];
        $isbn_buku = $_POST['isbn_buku'];

        $result = $this->borrowingModel->updateBorrowing($id_peminjaman, $tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku);

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

        if ($this->borrowingModel->getReturnStatus($id_peminjaman) == 1) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Peminjaman ini telah dikembalikan";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $isbn_buku = $this->borrowingModel->getIsbnByBorrowingId($id_peminjaman);
        
        if(!$this->bookModel->increaseAvailableQuantity($isbn_buku)) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan saat mengembalikan buku";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        

        $result = $this->borrowingModel->deleteBorrowing($id_peminjaman);

        if (!$result) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Terjadi kesalahan: " . $this->conn->error;
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        
        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Peminjaman dihapus" : "Terjadi kesalahan: " . $this->conn->error;

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
