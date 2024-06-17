<?php

namespace App\Controllers;



class HomeController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
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
        
        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        $rekomendasiBuku = $result->fetch_all(MYSQLI_ASSOC);

        // Include the view file
        include __DIR__ . '/../views/home.php';
    }

    public function bookSection () {
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
            $query = "SELECT * FROM Buku WHERE Judul LIKE '%$search%'";
        } else {
            $query = "SELECT * FROM Buku";
        }

        $result = $this->conn->query($query);

        if ($result === FALSE) {
            die("Error: " . $this->conn->error);
        }

        $buku = $result->fetch_all(MYSQLI_ASSOC);

        include __DIR__ . '/../views/book.php';
    }


}
