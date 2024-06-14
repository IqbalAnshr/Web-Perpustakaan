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
('Lantai 2', 200, 'Non-Fiksi', 'Rak buku non-fiksi lantai 2')";

if ($conn->query($sqlRakSeed) === TRUE) {
    echo "Data Rak berhasil di-seed.<br>";
} else {
    echo "Error seeding Rak: " . $conn->error . "<br>";
}

// Seeder untuk tabel Buku
$sqlBukuSeed = "INSERT INTO `Buku` (`ISBN`, `Judul`, `Penulis`, `Penerbit`, `Tahun_Terbit`, `Jumlah_Total`, `Jumlah_Tersedia`, `Status_Pinjam`, `ID_Rak`) VALUES 
('9781234567897', 'Buku Fiksi 1', 'Penulis A', 'Penerbit A', 2020, 10, 10, FALSE, 1),
('9780987654321', 'Buku Non-Fiksi 1', 'Penulis B', 'Penerbit B', 2021, 5, 5, FALSE, 2)";

if ($conn->query($sqlBukuSeed) === TRUE) {
    echo "Data Buku berhasil di-seed.<br>";
} else {
    echo "Error seeding Buku: " . $conn->error . "<br>";
}

// Seeder untuk tabel Transaksi Peminjaman
$sqlTransaksiPeminjamanSeed = "INSERT INTO `Transaksi_Peminjaman` (`Tanggal_Peminjaman`, `Tanggal_Pengembalian`, `NIM_Anggota`, `ISBN_Buku`) VALUES 
('2024-06-01', '2024-06-10', '1234567890', '9781234567897'),
('2024-06-05', '2024-06-15', '0987654321', '9780987654321')";

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

