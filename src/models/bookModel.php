<?php

namespace App\models;

class BookModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllBooks($search = '', $filter = '', $sort = 'Judul', $order = 'ASC', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $subQuery = "SELECT ISBN_Buku, COUNT(*) as Total_Peminjaman
                 FROM Transaksi_Peminjaman
                 GROUP BY ISBN_Buku";


        $query = "SELECT b.*, r.Lokasi, r.Kapasitas, r.Kategori, r.Keterangan, COALESCE(p.Total_Peminjaman, 0) as Total_Peminjaman 
                    FROM Buku b 
                    LEFT JOIN Rak r ON b.ID_Rak = r.ID_Rak 
                    LEFT JOIN ($subQuery) p ON b.ISBN = p.ISBN_Buku 
                    WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (b.ISBN LIKE '%$search%' OR b.Judul LIKE '%$search%' OR b.Penulis LIKE '%$search%' OR b.Penerbit LIKE '%$search%')";
        }

        if ($filter) {
            if ($filter == 'Popular') {
                $query .= " AND p.Total_Peminjaman IS NOT NULL";
            } else {
                $query .= " AND r.Kategori = '$filter'";
            }
        }

        $query .= " ORDER BY $sort $order LIMIT $limit OFFSET $offset";

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalBooks($search = '', $filter = '')
    {

        $sql = "SELECT COUNT(*) as total FROM buku b LEFT JOIN Rak r ON b.ID_Rak = r.ID_Rak WHERE 1=1";


        if (!empty($search)) {
            $sql .= " AND (b.Judul LIKE '%$search%' OR b.Penulis LIKE '%$search%' OR b.Penerbit LIKE '%$search%')";
        }


        if ($filter) {
            $sql .= " AND r.Kategori = '$filter'";
        }


        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();

        return $row['total'];
    }


    public function insertBook($isbn, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak)
    {
        $sql = "INSERT INTO buku (ISBN, Judul, Penulis, Penerbit, Tahun_Terbit, Jumlah_Total, Jumlah_Tersedia, Status_Pinjam, Sampul_Path, ID_Rak) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiiiiss", $isbn, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak);
        $result = $stmt->execute();

        return $result;
    }

    public function updateBook($isbn_baru, $isbn_lama, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak)
    {
        $stmt = $this->conn->prepare("UPDATE buku SET
            ISBN = ?,
            Judul = ?,
            Penulis = ?,
            Penerbit = ?,
            Tahun_Terbit = ?,
            Jumlah_Total = ?,
            Jumlah_Tersedia = ?,
            Status_Pinjam = ?,
            Sampul_Path = ?,
            ID_Rak = ?
            WHERE ISBN = ?");

        // Bind parameters
        $stmt->bind_param("sssssssssss", $isbn_baru, $judul, $penulis, $penerbit, $tahun_terbit, $jumlah_total, $jumlah_tersedia, $status_pinjam, $sampul_path, $id_rak, $isbn_lama);

        $result = $stmt->execute();

        return $result;
    }

    public function deleteBook($isbn)
    {
        $sql = "DELETE FROM buku WHERE ISBN = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn);
        $result = $stmt->execute();

        return $result;
    }

    public function getBookByISBN($isbn)
    {
        $sql = "SELECT b.*, r.* FROM buku b LEFT JOIN rak r ON b.ID_Rak = r.ID_Rak WHERE ISBN = ?  ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Metode untuk menambah jumlah buku tersedia
    public function increaseAvailableQuantity($isbn)
    {
        $sql = "UPDATE Buku SET Jumlah_Tersedia = Jumlah_Tersedia + 1 WHERE ISBN = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn);
        return $stmt->execute();
    }

    // Metode untuk mengurangi buku tersedia
    public function decreaseAvailableQuantity($isbn_buku)
    {
        $sql = "UPDATE Buku SET Jumlah_Tersedia = Jumlah_Tersedia - 1 WHERE ISBN = ? AND Jumlah_Tersedia > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn_buku);
        return $stmt->execute();
    }

    // Metode untuk memeriksa apakah buku tersedia
    public function isBookAvailable($isbn)
    {
        $sql = "SELECT Jumlah_Tersedia FROM Buku WHERE ISBN = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        return $book['Jumlah_Tersedia'] > 0;
    }

    public function isBookCanBeBorrowed($isbn)
    {
        $sql = "SELECT Status_Pinjam FROM Buku WHERE ISBN = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        return $book['Status_Pinjam'] == 1;
    }
}

