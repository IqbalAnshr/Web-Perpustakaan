<?php

namespace App\Controllers;

use App\models\BookModel;
use App\models\BorrowingModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class HomeController
{
    private $conn;
    private $config;
    private $bookModel;
    private $borrowingModel;


    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->bookModel = new BookModel($conn);
        $this->borrowingModel = new BorrowingModel($conn);
    }

    public function index()
    {
        // Query untuk menghitung buku dengan transaksi peminjaman terbanyak
        $query = "SELECT b.ISBN, b.Judul, COUNT(p.ID_Peminjaman) AS peminjaman 
                  FROM Buku b 
                  JOIN Transaksi_Peminjaman p ON b.ISBN = p.ISBN_Buku 
                  GROUP BY b.ISBN 
                  ORDER BY peminjaman DESC 
                  LIMIT 10";

        $query = "SELECT * FROM Buku LIMIT 10";

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        $books = $result->fetch_all(MYSQLI_ASSOC);

        include __DIR__ . '/../views/home.php';
    }

    public function bookSection()
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
        $totalBooks = $this->bookModel->getTotalBooks($search, $filter);
        $totalPages = ceil($totalBooks / $limit);
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $pages = range(1, $totalPages);

        include __DIR__ . '/../views/book.php';
    }

    public function detailBook()
    {
        $isbn = $_GET['isbn'];
        $book = $this->bookModel->getBookByISBN($isbn);
        $books = $this->bookModel->getAllBooks();

        if (!$book) {
            echo "Buku tidak ditemukan.";
            exit;
        }

        // var_dump($book);
        // exit;

        $search = '';

        include __DIR__ . '/../views/detail-book.php';
    }

    public function borrowingForm()
    {
        $isbn = $_GET['isbn'];

        if (empty($isbn)) {
            echo "ISBN tidak ditemukan. Coba lihat daftar buku <a href='/book'>disini</a>.";
            exit;
        }
        include __DIR__ . '/../views/form-borrowing.php';
    }


    public function requestVerification()
    {
        session_start(); // Tambahkan ini
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nim = $_POST['nim'];
            $isbn = $_POST['isbn'];

            $sql = "SELECT * FROM Anggota WHERE NIM = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $nim);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $mahasiswa = $result->fetch_assoc();
                $email = $mahasiswa['Email'];

                $kode_verifikasi = strtoupper(bin2hex(random_bytes(3)));

                $_SESSION['kode_verifikasi'] = $kode_verifikasi;
                $_SESSION['kode_verifikasi_timestamp'] = time();
                $_SESSION['nim'] = $nim;
                $_SESSION['isbn'] = $isbn;

                $this->sendVerificationEmail($email, $kode_verifikasi);

                $_SESSION['message'] = 'Kode verifikasi telah dikirim ke email Anda.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $_SESSION['message'] = 'NIM tidak ditemukan.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }


    public function processBorrowing()
    {
        session_start(); // Tambahkan ini
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode_verifikasi = $_POST['kode_verifikasi'];

            if (empty($kode_verifikasi)) {
                $_SESSION['message'] = 'Harap lengkapi semua kolom';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            if (isset($_SESSION['kode_verifikasi']) && isset($_SESSION['kode_verifikasi_timestamp'])) {
                $kode_terkirim = $_SESSION['kode_verifikasi'];
                $timestamp_terkirim = $_SESSION['kode_verifikasi_timestamp'];

                if ($kode_verifikasi === $kode_terkirim) {
                    if (time() - $timestamp_terkirim > 300) {
                        $_SESSION['message'] = 'Kode verifikasi telah kadaluarsa.';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit;
                    }
                    $_SESSION['isVerified'] = true;

                    $nim = $_SESSION['nim'];
                    $isbn = $_SESSION['isbn'];

                    $tanggal_peminjaman = date('Y-m-d');  // Tanggal hari ini
                    $tanggal_jatuh_tempo = date('Y-m-d', strtotime('+7 days'));  // 7 hari ke depan

                    if (!$this->bookModel->isBookAvailable($isbn)) {
                        $_SESSION['message'] = "Buku dengan ISBN $isbn tidak tersedia";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit;
                    }

                    if (!$this->bookModel->isBookCanBeBorrowed($isbn)) {
                        $_SESSION['message'] = "Buku dengan ISBN $isbn tidak dapat dipinjam";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit;
                    }

                    $result = $this->borrowingModel->insertBorrowing($tanggal_peminjaman, $tanggal_jatuh_tempo, $nim, $isbn);

                    if ($result) {
                        $this->bookModel->decreaseAvailableQuantity($isbn);

                        $book = $this->bookModel->getBookByISBN($isbn);

                        $sql = "SELECT * FROM Anggota WHERE NIM = ?";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bind_param('s', $nim);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $judul_buku = $book['Judul'];
                        $email = $result->fetch_assoc()['Email'];
                        $this->sendEmailBorrowing($email, $judul_buku, $isbn, $tanggal_peminjaman, $tanggal_jatuh_tempo);

                        unset($_SESSION['kode_verifikasi']);
                        unset($_SESSION['kode_verifikasi_timestamp']);
                        unset($_SESSION['nim']);

                        $_SESSION['message'] = 'Peminjaman berhasil.';
                        // header('Location: ' . $_SERVER['HTTP_REFERER']);
                        // var_dump($_SESSION);
                        // exit;
                        header('Location: /book/detail?isbn='.$_SESSION['isbn']);
                        unset($_SESSION['isbn']);
                        exit;
                    } else {
                        $_SESSION['message'] = 'Peminjaman gagal disimpan.';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit;
                    }
                } else {
                    $_SESSION['message'] = 'Kode verifikasi salah.';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            } else {
                $_SESSION['message'] = 'Kode verifikasi tidak ditemukan.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }

    private function sendVerificationEmail($to, $kode_verifikasi)
    {
        $mail = new PHPMailer(true);

        try {
            $mailConfig = $this->config['mail'];


            // var_dump($mailConfig);
            // exit;

            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->SMTPAuth = !empty($mailConfig['username']) && !empty($mailConfig['password']);
            if ($mail->SMTPAuth) {
                $mail->Username = $mailConfig['username'];
                $mail->Password = $mailConfig['password'];
            }
            $mail->Port = $mailConfig['port'];
            if (!empty($mailConfig['encryption'])) {
                $mail->SMTPSecure = $mailConfig['encryption'];
            }

            $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Kode Verifikasi Peminjaman Buku';
            $mail->Body = "Kode verifikasi Anda adalah: <b>$kode_verifikasi</b>";

            $mail->send();
        } catch (Exception $e) {
            error_log('Gagal mengirim email. Kesalahan: ' . $mail->ErrorInfo);
        }
    }

    public function sendEmailBorrowing($to, $judul_buku, $isbn, $tanggal_peminjaman, $tanggal_pengembalian)
    {
        $mail = new PHPMailer(true);

        try {
            $mailConfig = $this->config['mail'];

            // var_dump($mailConfig);
            // exit;

            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->SMTPAuth = !empty($mailConfig['username']) && !empty($mailConfig['password']);
            if ($mail->SMTPAuth) {
                $mail->Username = $mailConfig['username'];
                $mail->Password = $mailConfig['password'];
            }
            $mail->Port = $mailConfig['port'];
            if (!empty($mailConfig['encryption'])) {
                $mail->SMTPSecure = $mailConfig['encryption'];
            }

            $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
            $mail->addAddress($to);

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Informasi Peminjaman Buku';
            $mail->Body = $this->generateEmailBody($judul_buku, $isbn, $tanggal_peminjaman, $tanggal_pengembalian);

            $mail->send();
        } catch (Exception $e) {
            error_log('Gagal mengirim email. Kesalahan: ' . $mail->ErrorInfo);
        }
    }

    private function generateEmailBody($judul_buku, $isbn, $tanggal_peminjaman, $tanggal_pengembalian)
    {
        $body = '
        <html>
        <head>
            <style>
                .email-container {
                    font-family: Arial, sans-serif;
                    color: #333;
                    background-color: #f4f4f4;
                    padding: 20px;
                    border-radius: 8px;
                }
                .email-header {
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px;
                    border-radius: 8px 8px 0 0;
                }
                .email-content {
                    padding: 20px;
                }
                .email-footer {
                    margin-top: 20px;
                    padding: 10px;
                    font-size: 14px;
                    color: #6c757d;
                    background-color: #fff;
                    border-top: 1px solid #dee2e6;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h2>Informasi Peminjaman Buku</h2>
                </div>
                <div class="email-content">
                    <p>Halo,</p>
                    <p>Terima kasih telah meminjam buku di perpustakaan kami. Berikut adalah detail peminjaman Anda:</p>
                    <ul>
                        <li><strong>Judul Buku:</strong> ' . htmlspecialchars($judul_buku) . '</li>
                        <li><strong>ISBN:</strong> ' . htmlspecialchars($isbn) . '</li>
                        <li><strong>Tanggal Peminjaman:</strong> ' . htmlspecialchars($tanggal_peminjaman) . '</li>
                        <li><strong>Tanggal Pengembalian:</strong> ' . htmlspecialchars($tanggal_pengembalian) . '</li>
                    </ul>
                    <p>Harap mengembalikan buku pada atau sebelum tanggal pengembalian untuk menghindari denda keterlambatan sebesar Rp 3.000 per hari. Jika Anda terlambat mengembalikan buku, maka akan dikenakan sanksi denda sebesar Rp 3.000 per hari.</p>
                    <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
                    <p>Terima kasih atas perhatian Anda.</p>
                    <p>Salam hangat,<br>Perpustakaan</p>
                </div>
                <div class="email-footer">
                    <p>Jika Anda tidak melakukan peminjaman ini, harap laporkan segera ke administrator.</p>
                </div>
            </div>
        </body>
        </html>
    ';

        return $body;
    }



}



