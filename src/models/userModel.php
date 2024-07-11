<?php

namespace App\models;

class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllUsers($search = '', $order = 'ASC', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        // Validasi order by clause
        $order = strtoupper($order);
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC';
        }

        // SQL dasar
        $query = "SELECT * FROM User";

        // Tambahkan kondisi pencarian jika ada
        if (!empty($search)) {
            $search = $this->conn->real_escape_string($search); // Menghindari SQL Injection
            $query .= " WHERE User.Username LIKE '%$search%'";
        }

        // Tambahkan order by dan limit
        $query .= " ORDER BY User.Username $order LIMIT ? OFFSET ?";

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

    public function getTotalUsers($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM User";

        if (!empty($search)) {
            $search = $this->conn->real_escape_string($search);
            $sql .= " WHERE User.Username LIKE '%$search%'";
        }

        $result = $this->conn->query($sql);

        if ($result === FALSE) {
            die("Error executing query: " . $this->conn->error);
        }

        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function insertUser($username, $password, $role)
    {
        $sql = "INSERT INTO user (Username, Password, Role) VALUES ( ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $role);
        $result = $stmt->execute();
        return $result;
    }

    public function updateUser($username, $password, $role, $id_user) {
        $query = "UPDATE user SET Username = ?, Role = ?";

        // Periksa apakah password diisi
        if (!empty($password)) {
            $query .= ", Password = ?";
        }

        $query .= " WHERE ID_User = ?";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error preparing statement: " . $this->conn->error);
        }

        if (!empty($password)) {
            $stmt->bind_param("sssi", $username, $role, $password, $id_user);
        } else {
            $stmt->bind_param("ssi", $username, $role, $id_user);
        }

        $result = $stmt->execute();

        if ($result === FALSE) {
            die("Error executing statement: " . $stmt->error);
        }

        return $result;
    }
    public function deleteUser($id_user)
    {
        $sql = "DELETE FROM user WHERE ID_User = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_user);
        $result = $stmt->execute();

        return $result;
    }
}
