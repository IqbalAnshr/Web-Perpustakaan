<?php

namespace App\controllers;

use App\models\BookModel;

class BookController
{

    private $conn;
    private $bookModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->bookModel = new BookModel($conn);

        session_start();
        if (!isset($_SESSION['isLoggedIn']) && !isset($_SESSION['username'])) {
            header('Location: /admin/login');
            exit;
        }
    }


    public function index()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Judul';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;

        $queryString = http_build_query([
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
            'order' => $order,
        ]);

        $books = $this->bookModel->getAllBooks($search, $filter, $sort, $order, $page, $limit);
        $raks = $this->conn->query("SELECT ID_Rak, Kategori ,Keterangan FROM Rak")->fetch_all(MYSQLI_ASSOC);
        $totalBooks = $this->bookModel->getTotalBooks($search, $filter);
        $totalPages = ceil($totalBooks / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        include __DIR__ . '/../views/admin/books/index.php';
    }

    public function store()
    {
        $isbn = $_POST['isbn'];
        $judul = $_POST['judul'];
        $penulis = $_POST['penulis'];
        $penerbit = $_POST['penerbit'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $jumlah_total = $_POST['jumlah_total'];
        $jumlah_tersedia = $_POST['jumlah_tersedia'];
        $status_pinjam = $_POST['status_pinjam'];
        $id_rak = $_POST['id_rak'];

        if (empty($isbn) || empty($judul) || empty($penulis) || empty($penerbit) || empty($tahun_terbit) || empty($jumlah_total) || empty($status_pinjam) || empty($id_rak)) {
            $success = false;
            $message = "Harap lengkapi semua kolom";
            $_SESSION['message'] = $message;
            $_SESSION['success'] = $success;
            $redirect = $_SERVER['HTTP_REFERER'];
            header('Location: ' . $redirect);
            exit;
        }

        $sampul_path = null;

        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $sampul_name = $_FILES['file']['name'];
            $sampul_tmp_name = $_FILES['file']['tmp_name']; // Sumber file yang diunggah
            $upload_directory = 'uploads/cover_books/';
            $filename = uniqid() . '_' . $sampul_name;
            $destination = __DIR__ . '/../../public/' . $upload_directory;

            $sampul_path = $upload_directory . $filename;

            // Pastikan direktori tujuan tersedia, jika belum buat
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }

            // Pindahkan file yang diunggah ke direktori tujuan
            move_uploaded_file($sampul_tmp_name, $destination . $filename);
        }


        $result = $this->bookModel->insertBook($isbn, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak);

        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Buku ditambahkan" : "Terjadi kesalahan: " . $this->conn->error;

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


        $isbn_baru = $_POST['isbn_baru'];
        $isbn_lama = $_POST['isbn_lama'];
        $judul = $_POST['judul'];
        $penulis = $_POST['penulis'];
        $penerbit = $_POST['penerbit'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $jumlah_total = $_POST['jumlah_total'];
        $jumlah_tersedia = $_POST['jumlah_tersedia'];
        $status_pinjam = $_POST['status_pinjam'];
        $id_rak = $_POST['id_rak'];
        $old_sampul_path = $_POST['sampul_path'];

        $sampul_path = null;

        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $sampul_name = $_FILES['file']['name'];
            $sampul_tmp_name = $_FILES['file']['tmp_name']; // Sumber file yang diunggah
            $upload_directory = 'uploads/cover_books/';
            $filename = uniqid() . '_' . $sampul_name;
            $destination = __DIR__ . '/../../public/' . $upload_directory;
            $sampul_path = $upload_directory . $filename;

            if (!empty($old_sampul_path)) {

                $oldFileName = basename($old_sampul_path);

                if (file_exists($destination . $oldFileName)) {
                    if (!unlink($destination . $oldFileName)) {
                        echo ("$oldFileName cannot be deleted due to an error");
                        exit;
                    }
                }

            }

            // Pastikan direktori tujuan tersedia, jika belum buat
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }

            move_uploaded_file($sampul_tmp_name, $destination . $filename);
        }


        $result = $this->bookModel->updateBook($isbn_baru, $isbn_lama, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak);

        if ($result === FALSE) {
            $message = "Terjadi kesalahan: " . $this->conn->error;
            $success = false;
        } else {
            $message = "Buku diubah";
            $success = true;
        }

        $_SESSION['success'] = $success;
        $_SESSION['message'] = $message;

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

        // Ambil nilai ISBN dari form
        $isbn = $_POST['isbn'];
        $sampul_path = $_POST['sampul_path'];

        if (!empty($sampul_path)) {
            $oldFileName = basename($sampul_path);
            $destination = __DIR__ . '/../../public/uploads/cover_books/';
            if (file_exists($destination . $oldFileName)) {
                if (!unlink($destination . $oldFileName)) {
                    echo ("$oldFileName cannot be deleted due to an error");
                    exit;
                }
            }
        }


        $result = $this->bookModel->deleteBook($isbn);


        $_SESSION['success'] = $result;
        $_SESSION['message'] = $result ? "Buku dihapus" : "Terjadi kesalahan: " . $this->conn->error;

        $redirect = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $redirect);
        exit;
    }



}