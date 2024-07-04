<?php

namespace App\Models;

class BorrowingModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllBorrowings($search, $sort, $order, $page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM transaksi_peminjaman WHERE ISBN_Buku LIKE ? ORDER BY $sort $order LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($sql);
        $search = "%$search%";
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalBorrowings($search)
    {
        $sql = "SELECT COUNT(*) as total FROM transaksi_peminjaman WHERE ISBN_Buku LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $search = "%$search%";
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function insertBorrowing($tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku)
    {
        $sql = "INSERT INTO transaksi_peminjaman (Tanggal_Peminjaman, Tanggal_Pengembalian, NIM_Anggota, ISBN_Buku) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku);
        return $stmt->execute();
    }

    public function updateBorrowing($id_peminjaman, $tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku)
    {
        $sql = "UPDATE transaksi_peminjaman SET Tanggal_Peminjaman = ?, Tanggal_Pengembalian = ?, NIM_Anggota = ?, ISBN_Buku = ? WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $tanggal_peminjaman, $tanggal_pengembalian, $nim_anggota, $isbn_buku, $id_peminjaman);
        return $stmt->execute();
    }

    public function deleteBorrowing($id_peminjaman)
    {
        $sql = "DELETE FROM transaksi_peminjaman WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_peminjaman);
        return $stmt->execute();
    }
}
