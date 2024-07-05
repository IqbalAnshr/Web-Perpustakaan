<?php

namespace App\Models;

class BorrowingModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllBorrowings($search, $tgl_peminjaman, $tgl_pengembalian, $sort, $order, $page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $sql = "
        SELECT tp.*, a.Nama AS Nama_Anggota, b.Judul AS Judul_Buku
        FROM Transaksi_Peminjaman tp
        LEFT JOIN Anggota a ON tp.NIM_Anggota = a.NIM
        LEFT JOIN Buku b ON tp.ISBN_Buku = b.ISBN
        WHERE (b.ISBN LIKE ? OR a.NIM LIKE ?)
    ";

        // Tambahkan filter tanggal peminjaman
        if (!empty($tgl_peminjaman)) {
            $sql .= " AND tp.Tanggal_Peminjaman >= '$tgl_peminjaman'";
        }

        // Tambahkan filter tanggal pengembalian
        if (!empty($tgl_pengembalian)) {
            $sql .= " AND tp.Tanggal_Jatuh_Tempo <= '$tgl_pengembalian'";
        }

        $sql .= " ORDER BY $sort $order
        LIMIT $limit OFFSET $offset
    ";

        $stmt = $this->conn->prepare($sql);
        $search = "%$search%";
        $stmt->bind_param('ss', $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalBorrowings($search, $tgl_peminjaman, $tgl_pengembalian)
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM Transaksi_Peminjaman tp
        LEFT JOIN Anggota a ON tp.NIM_Anggota = a.NIM
        LEFT JOIN Buku b ON tp.ISBN_Buku = b.ISBN
        WHERE (b.ISBN LIKE ? OR a.NIM LIKE ?)
    ";

        // Tambahkan filter tanggal peminjaman
        if (!empty($tgl_peminjaman)) {
            $sql .= " AND tp.Tanggal_Peminjaman >= '$tgl_peminjaman'";
        }

        // Tambahkan filter tanggal pengembalian
        if (!empty($tgl_pengembalian)) {
            $sql .= " AND tp.Tanggal_Jatuh_Tempo <= '$tgl_pengembalian'";
        }

        $stmt = $this->conn->prepare($sql);
        $search = "%$search%";
        $stmt->bind_param('ss', $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function getBorrowingById($id_peminjaman)
    {
        $sql = "SELECT * FROM transaksi_peminjaman WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getIsbnByBorrowingId($id_peminjaman){
        $sql = "SELECT ISBN_Buku FROM transaksi_peminjaman WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['ISBN_Buku'];
    }

    public function getReturnStatus($id_peminjaman)
    {
        $sql = "SELECT Status_Pengembalian FROM transaksi_peminjaman WHERE ID_Peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Status_Pengembalian'];
    }

    public function insertBorrowing($tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku)
    {
        $sql = "INSERT INTO transaksi_peminjaman (Tanggal_Peminjaman, Tanggal_Jatuh_Tempo, NIM_Anggota, ISBN_Buku) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku);
        return $stmt->execute();
    }

    public function updateBorrowing($id_peminjaman, $tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku)
    {
        $sql = "UPDATE transaksi_peminjaman SET Tanggal_Peminjaman = ?, Tanggal_Jatuh_Tempo = ?, NIM_Anggota = ?, ISBN_Buku = ? WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $tanggal_peminjaman, $tanggal_jatuh_tempo, $nim_anggota, $isbn_buku, $id_peminjaman);
        return $stmt->execute();
    }

    public function deleteBorrowing($id_peminjaman)
    {
        $sql = "DELETE FROM transaksi_peminjaman WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        return $stmt->execute();
    }

    // Metode untuk mengembalikan status peminjaman
    public function markAsReturned($id_peminjaman)
    {
        $sql = "UPDATE transaksi_peminjaman SET Status_Pengembalian = TRUE WHERE ID_Peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        return $stmt->execute();
    }

    // Metode untuk mengubah status peminjaman
    public function markAsNotReturned($id_peminjaman)
    {
        $sql = "UPDATE Transaksi_Peminjaman SET Status_Pengembalian = FALSE WHERE ID_Peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        return $stmt->execute();
    }


}
