<?php

// Koneksi ke database
require_once __DIR__ . '/config/koneksi.php';

// Seeder untuk tabel User
$sqlUserSeed = "INSERT INTO `User` (`Username`, `Password`, `Role`) VALUES 
('admin', '".password_hash('admin123', PASSWORD_DEFAULT)."', 'admin'),
('user1', '".password_hash('user123', PASSWORD_DEFAULT)."', 'user')";

if ($conn->query($sqlUserSeed) === TRUE) {
    echo "Data User berhasil di-seed.<br>";
} else {
    echo "Error seeding User: " . $conn->error . "<br>";
}

// Seeder untuk tabel Anggota
$sqlAnggotaSeed = "INSERT INTO `Anggota` (`NIM`, `Nama`, `Alamat`, `No_Telepon`, `Email`) VALUES 
('1234567890', 'John Doe', 'Jl. Sudirman No. 1', '081234567890', 'johndoe@example.com'),
('0987654321', 'Jane Doe', 'Jl. Thamrin No. 2', '081098765432', 'janedoe@example.com')";

if ($conn->query($sqlAnggotaSeed) === TRUE) {
    echo "Data Anggota berhasil di-seed.<br>";
} else {
    echo "Error seeding Anggota: " . $conn->error . "<br>";
}

// Seeder untuk tabel Rak
$sqlRakSeed = "INSERT INTO `Rak` (`Lokasi`, `Kapasitas`, `Kategori`, `Keterangan`) VALUES
  ('Lantai 1', 100, 'Fiksi', 'Rak buku fiksi lantai 1'),
  ('Lantai 2', 200, 'Non-Fiksi', 'Rak buku non-fiksi lantai 2'),
  ('Lantai 3', 80, 'Anak-anak', 'Rak buku anak-anak lantai 3'),
  ('Lantai 3', 50, 'Majalah', 'Rak majalah lantai 3'),
  ('Lantai 4', 150, 'Fiksi', 'Rak buku fiksi lantai 4'),
  ('Lantai 4', 120, 'Non-Fiksi', 'Rak buku non-fiksi lantai 4'),
  ('Lantai 5', 100, 'Referensi', 'Rak buku referensi lantai 5'),
  ('Lantai 6', 80, 'Majalah Dewasa', 'Rak buku Dewasa lantai 5')";

if ($conn->query($sqlRakSeed) === TRUE) {
    echo "Data Rak berhasil di-seed.<br>";
} else {
    echo "Error seeding Rak: " . $conn->error . "<br>";
}

// Seeder untuk tabel Buku
$sqlBukuSeed = "INSERT INTO Buku (ISBN, Judul, Penulis, Penerbit, Tahun_Terbit, Jumlah_Total, Jumlah_Tersedia, Status_Pinjam, Sampul_Path, ID_Rak)
VALUES
  ('1234567890123', 'The Lord of the Rings', 'J.R.R. Tolkien', 'Allen & Unwin', 1954, 5, 5, FALSE, 'images/lotr.jpg', 1),
  ('9876543210987', 'The Hobbit', 'J.R.R. Tolkien', 'Allen & Unwin', 1937, 3, 3, FALSE, 'images/hobbit.jpg', 1),
  ('0123456789012', 'Pride and Prejudice', 'Jane Austen', 'Penguin Books', 1813, 8, 6, FALSE, 'images/pride.jpg', 2),
  ('7894561230456', 'To Kill a Mockingbird', 'Harper Lee', 'HarperCollins', 1960, 10, 8, FALSE, 'images/mockingbird.jpg', 3),
  ('3456789012345', 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', 1951, 7, 5, FALSE, 'images/catcher.jpg', 3),
  ('6789012345678', '1984', 'George Orwell', 'Secker & Warburg', 1949, 6, 4, FALSE, 'images/1984.jpg', 4),
  ('9012345678901', 'Animal Farm', 'George Orwell', 'Secker & Warburg', 1945, 9, 7, FALSE, 'images/animalfarm.jpg', 4),
  ('2345678901234', 'Brave New World', 'Aldous Huxley', 'Chatto & Windus', 1932, 12, 10, FALSE, 'images/bravenewworld.jpg', 4),
  ('5678901234567', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Charles Scribners Sons', 1925, 8, 6, FALSE, 'images/gatsby.jpg', 5),
  ('8901234567890', 'One Hundred Years of Solitude', 'Gabriel García Márquez', 'Editorial Sudamericana', 1967, 15, 12, FALSE, 'images/solitude.jpg', 5),
  ('1234567890122', 'The Grapes of Wrath', 'John Steinbeck', 'Viking Press', 1939, 11, 9, FALSE, 'images/grapes.jpg', 6),
  ('4567890123456', 'Invisible Man', 'Ralph Ellison', 'Random House', 1952, 9, 7, FALSE, 'images/invisibleman.jpg', 6),
  ('7890123456789', 'Beloved', 'Toni Morrison', 'Alfred A. Knopf', 1987, 14, 11, FALSE, 'images/beloved.jpg', 6)";

if ($conn->query($sqlBukuSeed) === TRUE) {
    echo "Data Buku berhasil di-seed.<br>";
} else {
    echo "Error seeding Buku: " . $conn->error . "<br>";
}

// Seeder untuk tabel Transaksi Peminjaman
$sqlTransaksiPeminjamanSeed = "INSERT INTO `Transaksi_Peminjaman` (`Tanggal_Peminjaman`, `Tanggal_Pengembalian`, `NIM_Anggota`, `ISBN_Buku`) VALUES 
('2024-06-01', '2024-06-10', '1234567890', '5678901234567'),
('2024-06-05', '2024-06-15', '0987654321', '7890123456789')";

if ($conn->query($sqlTransaksiPeminjamanSeed) === TRUE) {
    echo "Data Transaksi Peminjaman berhasil di-seed.<br>";
} else {
    echo "Error seeding Transaksi Peminjaman: " . $conn->error . "<br>";
}

// Seeder untuk tabel Transaksi Pengembalian
$sqlTransaksiPengembalianSeed = "INSERT INTO `Transaksi_Pengembalian` (`Tanggal_Pengembalian`, `Denda`, `ID_Peminjaman`) VALUES 
('2024-06-10', 0.00, 1),
('2024-06-15', 0.00, 2)";

if ($conn->query($sqlTransaksiPengembalianSeed) === TRUE) {
    echo "Data Transaksi Pengembalian berhasil di-seed.<br>";
} else {
    echo "Error seeding Transaksi Pengembalian: " . $conn->error . "<br>";
}

// Menutup koneksi database
$conn->close();

