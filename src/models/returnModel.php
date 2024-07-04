<?php

namespace App\Models;

class ReturnModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllReturns($search = '', $filter = '', $sort = 'Tanggal_Pengembalian', $order = 'ASC', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT rp.*, tp.ID_Peminjaman, a.Nama, b.Judul, tp.NIM_Anggota, tp.ISBN_Buku 
                  FROM transaksi_pengembalian rp 
                  LEFT JOIN Transaksi_Peminjaman tp ON rp.ID_Peminjaman = tp.ID_Peminjaman 
                  LEFT JOIN Anggota a ON tp.NIM_Anggota = a.NIM 
                  LEFT JOIN Buku b ON tp.ISBN_Buku = b.ISBN 
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (rp.ID_Pengembalian LIKE '%$search%' OR rp.Tanggal_Pengembalian LIKE '%$search%' OR rp.Denda LIKE '%$search%' OR a.Nama LIKE '%$search%' OR b.Judul LIKE '%$search%' OR tp.NIM_Anggota LIKE '%$search%' OR tp.ISBN_Buku LIKE '%$search%')";
        }

        if (!empty($filter)) {
            $query .= " AND rp.Tanggal_Pengembalian = '$filter'";
        }

        $query .= " ORDER BY $sort $order LIMIT $limit OFFSET $offset";

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllReturns($search = '', $filter = '')
    {
        $query = "SELECT COUNT(*) as total 
                  FROM transaksi_pengembalian rp 
                  LEFT JOIN Transaksi_Peminjaman tp ON rp.ID_Peminjaman = tp.ID_Peminjaman 
                  LEFT JOIN Anggota a ON tp.NIM_Anggota = a.NIM 
                  LEFT JOIN Buku b ON tp.ISBN_Buku = b.ISBN 
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (rp.ID_Pengembalian LIKE '%$search%' OR rp.Tanggal_Pengembalian LIKE '%$search%' OR rp.Denda LIKE '%$search%' OR a.Nama LIKE '%$search%' OR b.Judul LIKE '%$search%' OR tp.NIM_Anggota LIKE '%$search%' OR tp.ISBN_Buku LIKE '%$search%')";
        }

        if (!empty($filter)) {
            $query .= " AND rp.Tanggal_Pengembalian = '$filter'";
        }

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getUnreturnedTransactions()
    {
        $sql = "SELECT tp.ID_Peminjaman, b.Judul, a.Nama, a.NIM, tp.ISBN_Buku
            FROM Transaksi_Peminjaman tp
            LEFT JOIN Buku b ON tp.ISBN_Buku = b.ISBN
            LEFT JOIN Anggota a ON tp.NIM_Anggota = a.NIM
            WHERE tp.ID_Peminjaman NOT IN (
                SELECT ID_Peminjaman FROM transaksi_pengembalian
            )";
        $result = $this->conn->query($sql);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addReturn($id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda)
    {
        $sql = "INSERT INTO transaksi_pengembalian (ID_Pengembalian, ID_Peminjaman, Tanggal_Pengembalian, Denda) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iisd', $id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda);
        $stmt->execute();
    }

    public function editReturn($id_pengembalian, $id_peminjaman, $tanggal_pengembalian, $denda)
    {
        $sql = "UPDATE transaksi_pengembalian SET ID_Peminjaman = ?, Tanggal_Pengembalian = ?, Denda = ? WHERE ID_Pengembalian = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('issi', $id_peminjaman, $tanggal_pengembalian, $denda, $id_pengembalian);
        $stmt->execute();
    }

    public function deleteReturn($id_pengembalian)
    {
        $sql = "DELETE FROM transaksi_pengembalian WHERE ID_Pengembalian = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_pengembalian);
        $stmt->execute();
    }
}
