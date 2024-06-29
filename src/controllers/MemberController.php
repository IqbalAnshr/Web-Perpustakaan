<?php

namespace App\controllers;

use App\models\MemberModel;

class MemberController
{

     private $conn;
     private $memberModel;

     public function __construct($conn)
     {
     $this->conn = $conn;
     $this->memberModel = new MemberModel($conn);
     }

     public function index(){
          $search = isset($_GET['search']) ? $_GET['search'] : '';
          $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Nama';
          $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
          $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
          $limit = 10;
      
          $queryString = http_build_query([
              'search' => $search,
              'order' => $order
          ]);
      
          // Mengambil data anggota dan total anggota
          $members = $this->memberModel->getAllMembers($search, $sort, $order, $page, $limit);
          $totalMembers = $this->memberModel->getTotalMembers($search);
          $totalPages = ceil($totalMembers / $limit);
          $previousPage = $page - 1;
          $nextPage = $page + 1;
          $pages = range(1, $totalPages);
      
          // Memastikan nilai previousPage dan nextPage tidak keluar dari batas halaman
          if ($previousPage < 1) {
              $previousPage = 1;
          }
      
          if ($nextPage > $totalPages) {
              $nextPage = $totalPages;
          }
      
          // Menyertakan file view
          include __DIR__ . '/../views/admin/members/index.php';
          exit;
      }
      

     public function store()
     {
         $nim = $_POST['nim'];
         $nama = $_POST['nama'];
         $alamat = $_POST['alamat'];
         $no_telepon = $_POST['no_telepon'];
         $email = $_POST['email'];

         if (empty($nim) || empty($nama) || empty($alamat) || empty($no_telepon) || empty($email)) {
             $success = false;
             $message = "Harap lengkapi semua kolom";
             $_SESSION['message'] = $message;
             $_SESSION['success'] = $success;
             $redirect = $_SERVER['HTTP_REFERER'];
             header('Location: ' . $redirect);
             exit;
         }
          $result = $this->memberModel->insertmember($nim, $nama, $alamat, $no_telepon, $email);
          $_SESSION['success'] = $result;
          $_SESSION['message'] = $result ? "Member ditambahkan" : "Terjadi kesalahan: " . $this->conn->error;

          $redirect = $_SERVER['HTTP_REFERER'];
          header('Location: ' . $redirect);
         //refresh halaman
          exit;
     }

     public function update()
     {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('HTTP/1.1 405 Method Not Allowed');
             exit;
         }
     
         // Ambil data dari POST request
         $nim_baru = $_POST['nim_baru'];
         $nim_lama = $_POST['nim_lama'];
         $nama = $_POST['nama'];
         $alamat = $_POST['alamat'];
         $no_telepon = $_POST['no_telepon'];
         $email = $_POST['email'];
     
         // Update anggota
         $result = $this->memberModel->updateMember($nim_baru, $nim_lama, $nama, $alamat, $no_telepon, $email);
         if ($result === FALSE) {
             $message = "Terjadi kesalahan: " . $this->conn->error;
             $success = false;
         } else {
             $message = "Member diubah";
             $success = true;
         }
     
         // Set session messages
         $_SESSION['success'] = $success;
         $_SESSION['message'] = $message;
     
         // Redirect ke halaman sebelumnya
         $redirect = $_SERVER['HTTP_REFERER'];
         header('Location: ' . $redirect);
         exit;
     }
     

     public function destroy()
     {
          if ($_POST['_method'] !== 'DELETE') {
               header('HTTP/1.1 405 Method Not Allowed');
               exit;
          }
           // Ambil nilai NIM dari form
          $nim = $_POST['nim'];
          $result = $this->memberModel->deleteMember($nim);
          $_SESSION['success'] = $result;
          $_SESSION['message'] = $result ? "Member dihapus" : "Terjadi kesalahan: " . $this->conn->error;
          
          $redirect = $_SERVER['HTTP_REFERER'];
          header('Location: ' . $redirect);
          exit;
     }

}