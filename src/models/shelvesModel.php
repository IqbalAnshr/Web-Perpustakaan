<?php

namespace App\models;

class ShelvesModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllShelves($search = '', $filter = '', $sort = 'ID_Rak', $order = 'ASC', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT * FROM rak WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (ID_Rak LIKE '%$search%' OR Lokasi LIKE '%$search%' OR Kapasitas LIKE '%$search%' OR Kategori LIKE '%$search%' OR Keterangan LIKE '%$search%')";
        }

        if (!empty($filter)) {
            $query .= " AND Kategori = '$filter'";
        }

        $query .= " ORDER BY $sort $order LIMIT $limit OFFSET $offset";

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllShelvesWithBookCount($search = '', $filter = '', $sort = 'ID_Rak', $order = 'ASC', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $query = "
        SELECT rak.*, COUNT(buku.ISBN) AS JumlahBuku
        FROM rak
        LEFT JOIN buku ON rak.ID_Rak = buku.ID_Rak
        WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (rak.ID_Rak LIKE '%$search%' OR Lokasi LIKE '%$search%' OR Kapasitas LIKE '%$search%' OR Kategori LIKE '%$search%' OR Keterangan LIKE '%$search%')";
        }

        if (!empty($filter)) {
            $query .= " AND Kategori = '$filter'";
        }

        $query .= "
        GROUP BY rak.ID_Rak
        ORDER BY $sort $order
        LIMIT $limit OFFSET $offset";

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getTotalShelves($search = '', $filter = '')
    {
        $sql = "SELECT COUNT(*) as total FROM rak WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (ID_Rak LIKE '%$search%' OR Lokasi LIKE '%$search%' OR Kapasitas LIKE '%$search%' OR Kategori LIKE '%$search%' OR Keterangan LIKE '%$search%')";
        }

        if (!empty($filter)) {
            $sql .= " AND Kategori = '$filter'";
        }

        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function insertShelf($lokasi, $kapasitas, $kategori, $keterangan)
    {
        $sql = "INSERT INTO rak ( Lokasi, Kapasitas, Kategori, Keterangan) VALUES ( ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siss", $lokasi, $kapasitas, $kategori, $keterangan);
        $result = $stmt->execute();

        return $result;
    }

    public function updateShelf($id_rak, $lokasi, $kapasitas, $kategori, $keterangan)
    {
        $sql = "UPDATE rak SET Lokasi = ?, Kapasitas = ?, Kategori = ?, Keterangan = ? WHERE ID_Rak = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisss", $lokasi, $kapasitas, $kategori, $keterangan, $id_rak);
        $result = $stmt->execute();

        return $result;
    }

    public function deleteShelf($id_rak)
    {
        $sql = "DELETE FROM rak WHERE ID_Rak = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $id_rak);
        $result = $stmt->execute();

        return $result;
    }
}