<?php

namespace App\Controllers;
use App\models\BookModel;



class HomeController
{
    private $conn;
    private $bookModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->bookModel = new BookModel($conn);
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


}
