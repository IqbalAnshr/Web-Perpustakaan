<?php

namespace App\Models;

class ReturnModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllReturns()
    {
        $sql = "SELECT * FROM transaksi_pengembalian";
        $result = $this->conn->query($sql);

        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }

        return $returns;
    }

    public function countAllReturns()
    {
        $sql = "SELECT COUNT(*) as total FROM transaksi_pengembalian";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getReturnsByPage($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM transaksi_pengembalian LIMIT $offset, $perPage";
        $result = $this->conn->query($sql);

        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }

        return $returns;
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
