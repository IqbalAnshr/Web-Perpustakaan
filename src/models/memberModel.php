<?php

namespace App\models;

class MemberModel
{
     private $conn;

     public function __construct($conn)
     {
          $this->conn = $conn;
     }

     public function getAllMembers($search = '', $order = 'ASC', $page = 1, $limit = 10)
     {
         $offset = ($page - 1) * $limit;
         
         // Validasi order by clause
         $order = strtoupper($order);
         if ($order !== 'ASC' && $order !== 'DESC') {
             $order = 'ASC';
         }
         
         // SQL dasar
         $query = "SELECT * FROM Anggota";
         
         // Tambahkan kondisi pencarian jika ada
         if (!empty($search)) {
             $search = $this->conn->real_escape_string($search); // Menghindari SQL Injection
             $query .= " WHERE Anggota.Nama LIKE '%$search%' OR Anggota.Nim LIKE '%$search%' OR Anggota.Alamat LIKE '%$search%'";
         }
         
         // Tambahkan order by dan limit
         $query .= " ORDER BY Anggota.Nama $order LIMIT ? OFFSET ?";
         
         // Prepare statement
         $stmt = $this->conn->prepare($query);
         
         if (!$stmt) {
             die("Error preparing statement: " . $this->conn->error); // Tangani jika ada kesalahan dalam persiapan statement
         }
         
         // Bind parameter
         $stmt->bind_param("ii", $limit, $offset);
         
         // Eksekusi statement
         $stmt->execute();
         
         // Ambil hasilnya
         $result = $stmt->get_result();
         
         if ($result === FALSE) {
             die("Error executing statement: " . $stmt->error); // Tangani jika ada kesalahan dalam eksekusi statement
         }
         
         // Kembalikan hasil sebagai array asosiatif
         return $result->fetch_all(MYSQLI_ASSOC);
     }
     
     

     public function getTotalMembers($search = '')
     {
         // SQL dasar untuk menghitung total anggota
         $sql = "SELECT COUNT(*) as total FROM Anggota";
     
         // Tambahkan kondisi pencarian jika ada
         if (!empty($search)) {
             $search = $this->conn->real_escape_string($search); // Menghindari SQL Injection
             $sql .= " WHERE Anggota.Nama LIKE '%$search%' OR Anggota.Nim LIKE '%$search%' OR Anggota.Alamat LIKE '%$search%'";
         }
     
         // Eksekusi query
         $result = $this->conn->query($sql);
     
         if ($result === FALSE) {
             die("Error executing query: " . $this->conn->error); // Tangani jika ada kesalahan dalam eksekusi query
         }
     
         // Ambil hasilnya
         $row = $result->fetch_assoc();
     
         return $row['total'];
     }
     


     public function insertMember($nim, $nama, $alamat, $no_telepon, $email)
     {
          $sql = "INSERT INTO Anggota (NIM, Nama, Alamat, No_Telepon, Email) VALUES (?, ?, ?, ?, ?)";
          $stmt = $this->conn->prepare($sql);
          $stmt->bind_param("issis", $nim, $nama, $alamat, $no_telepon, $email,);
          $result = $stmt->execute();
          return $result;
     }

public function updateMember($nim_baru, $nim_lama, $nama, $alamat, $no_telepon, $email)
{
    // Prepare statement
    $stmt = $this->conn->prepare("UPDATE Anggota SET
        NIM = ?,
        Nama = ?,
        Alamat = ?,
        No_Telepon = ?,
        Email = ?
        WHERE NIM = ?");
    
    if (!$stmt) {
        die("Error preparing statement: " . $this->conn->error); // Tangani jika ada kesalahan dalam persiapan statement
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $nim_baru, $nama, $alamat, $no_telepon, $email, $nim_lama);

    // Eksekusi statement
    $result = $stmt->execute();

    if ($result === FALSE) {
        die("Error executing statement: " . $stmt->error); // Tangani jika ada kesalahan dalam eksekusi statement
    }

    return $result;
}


     public function deleteMember($nim)
     {
         $sql = "DELETE FROM anggota WHERE NIM = ?";
         $stmt = $this->conn->prepare($sql);
         $stmt->bind_param('i', $nim);
         $result = $stmt->execute();
 
         return $result;
     }


}