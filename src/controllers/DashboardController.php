<?php

namespace App\controllers;

use App\models\BookModel;
use App\models\RakModel;

class DashboardController
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        // Query untuk mendapatkan jumlah anggota
        $result = $this->conn->query("SELECT COUNT(*) AS Total_Anggota FROM Anggota");
        $totalAnggota = $result->fetch_assoc()['Total_Anggota'];

        // Query untuk mendapatkan jumlah buku
        $result = $this->conn->query("SELECT COUNT(*) AS Total_Buku FROM Buku");
        $totalBuku = $result->fetch_assoc()['Total_Buku'];

        // Query untuk mendapatkan jumlah peminjaman
        $result = $this->conn->query("SELECT COUNT(*) AS Total_Peminjaman FROM Transaksi_Peminjaman");
        $totalPeminjaman = $result->fetch_assoc()['Total_Peminjaman'];

        // Query untuk mendapatkan transaksi peminjaman yang belum dikembalikan
        $result = $this->conn->query("SELECT COUNT(*) AS Belum_Dikembalikan FROM Transaksi_Peminjaman WHERE Status_Pengembalian = FALSE");
        $belumDikembalikan = $result->fetch_assoc()['Belum_Dikembalikan'];

        // Query untuk mendapatkan 5 buku terbaru
        $result = $this->conn->query("SELECT * FROM Buku ORDER BY Tahun_Terbit DESC LIMIT 5");
        $bukuTerbaru = $result->fetch_all(MYSQLI_ASSOC);

        // Query untuk mendapatkan 5 anggota terbaru
        $result = $this->conn->query("SELECT * FROM Anggota ORDER BY NIM DESC LIMIT 5");
        $anggotaTerbaru = $result->fetch_all(MYSQLI_ASSOC);

        // Query untuk mendapatkan 5 transaksi peminjaman terbaru
        $result = $this->conn->query("SELECT * FROM Transaksi_Peminjaman ORDER BY Tanggal_Peminjaman DESC LIMIT 5");
        $peminjamanTerbaru = $result->fetch_all(MYSQLI_ASSOC);

        // Query untuk mendapatkan 5 pengembalian terbaru
        $result = $this->conn->query("SELECT * FROM Transaksi_Pengembalian ORDER BY Tanggal_Pengembalian DESC LIMIT 5");
        $pengembalianTerbaru = $result->fetch_all(MYSQLI_ASSOC);

        // Query untuk mendapatkan buku yang tidak tersedia
        $result = $this->conn->query("SELECT * FROM Buku WHERE Jumlah_Tersedia = 0");
        $bukuTidakTersedia = $result->fetch_all(MYSQLI_ASSOC);

        // Query untuk mendapatkan anggota dengan transaksi terbanyak
        $result = $this->conn->query("SELECT a.NIM, a.Nama, COUNT(tp.ID_Peminjaman) AS Jumlah_Transaksi
                                        FROM Anggota a
                                        LEFT JOIN Transaksi_Peminjaman tp ON a.NIM = tp.NIM_Anggota
                                        GROUP BY a.NIM, a.Nama
                                        ORDER BY Jumlah_Transaksi DESC
                                        LIMIT 5");
        $anggotaTerbanyak = $result->fetch_all(MYSQLI_ASSOC);

        $sqlPeminjaman = "SELECT MONTH(Tanggal_Peminjaman) as Bulan, COUNT(*) as Jumlah FROM Transaksi_Peminjaman GROUP BY MONTH(Tanggal_Peminjaman) ORDER BY MONTH(Tanggal_Peminjaman)";
        $resultPeminjaman = $this->conn->query($sqlPeminjaman);

        $labels = [];
        $dataPeminjaman = [];
        while ($row = $resultPeminjaman->fetch_assoc()) {
            $labels[] = date('F', mktime(0, 0, 0, $row['Bulan'], 10));
            $dataPeminjaman[] = $row['Jumlah'];
        }

        // Query untuk mendapatkan total denda per bulan
        $sqlDenda = "SELECT MONTH(Tanggal_Pengembalian) as Bulan, SUM(Denda) + 3000 as TotalDenda FROM Transaksi_Pengembalian GROUP BY MONTH(Tanggal_Pengembalian) ORDER BY MONTH(Tanggal_Pengembalian)";
        $resultDenda = $this->conn->query($sqlDenda);

        $dataDenda = [];
        while ($row = $resultDenda->fetch_assoc()) {
            $dataDenda[] = $row['TotalDenda'];
        }
        

        // Mengirim data ke tampilan
        include __DIR__ . '/../views/admin/dashboard.php';
    }

}
