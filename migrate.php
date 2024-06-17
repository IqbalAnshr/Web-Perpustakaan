<?php

// Koneksi ke database
require_once __DIR__ . '/config/koneksi.php';

// Query untuk membuat tabel User
$sqlUser = "CREATE TABLE IF NOT EXISTS `User` (
    `ID_User` INT AUTO_INCREMENT PRIMARY KEY,
    `Username` VARCHAR(50) NOT NULL,
    `Password` VARCHAR(255) NOT NULL,
    `Role` VARCHAR(20) NOT NULL
)";

if ($conn->query($sqlUser) === TRUE) {
    echo "Tabel User berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table User: " . $conn->error . "<br>";
}

// Query untuk membuat tabel Data Anggota
$sqlDataAnggota = "CREATE TABLE IF NOT EXISTS `Anggota` (
    `NIM` VARCHAR(10) PRIMARY KEY,
    `Nama` VARCHAR(100) NOT NULL,
    `Alamat` VARCHAR(255) NOT NULL,
    `No_Telepon` VARCHAR(20) NOT NULL,
    `Email` VARCHAR(100) NOT NULL
)";

if ($conn->query($sqlDataAnggota) === TRUE) {
    echo "Tabel Data Anggota berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Query untuk membuat tabel Rak
$sqlRak = "CREATE TABLE IF NOT EXISTS `Rak` (
    `ID_Rak` INT AUTO_INCREMENT PRIMARY KEY,
    `Lokasi` VARCHAR(100) NOT NULL,
    `Kapasitas` INT NOT NULL,
    `Kategori` VARCHAR(50),
    `Keterangan` VARCHAR(255)
)";

if ($conn->query($sqlRak) === TRUE) {
    echo "Tabel Rak berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Query untuk membuat tabel Data Buku
$sqlDataBuku = "CREATE TABLE IF NOT EXISTS `Buku` (
    `ISBN` VARCHAR(20) PRIMARY KEY,
    `Judul` VARCHAR(255) NOT NULL,
    `Penulis` VARCHAR(100) NOT NULL,
    `Penerbit` VARCHAR(100) NOT NULL,
    `Tahun_Terbit` INT NOT NULL,
    `Jumlah_Total` INT NOT NULL,
    `Jumlah_Tersedia` INT NOT NULL,
    `Status_Pinjam` BOOLEAN DEFAULT FALSE,
    `Sampul_Path` VARCHAR(255),
    `ID_Rak` INT,
    FOREIGN KEY (`ID_Rak`) REFERENCES `Rak`(`ID_Rak`)
)";

if ($conn->query($sqlDataBuku) === TRUE) {
    echo "Tabel Data Buku berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Query untuk membuat tabel Transaksi Peminjaman
$sqlTransaksiPeminjaman = "CREATE TABLE IF NOT EXISTS `Transaksi_Peminjaman` (
    `ID_Peminjaman` INT AUTO_INCREMENT PRIMARY KEY,
    `Tanggal_Peminjaman` DATE NOT NULL,
    `Tanggal_Pengembalian` DATE NOT NULL,
    `NIM_Anggota` VARCHAR(10),
    `ISBN_Buku` VARCHAR(20),
    FOREIGN KEY (`NIM_Anggota`) REFERENCES `Anggota`(`NIM`),
    FOREIGN KEY (`ISBN_Buku`) REFERENCES `Buku`(`ISBN`)
)";

if ($conn->query($sqlTransaksiPeminjaman) === TRUE) {
    echo "Tabel Transaksi Peminjaman berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Query untuk membuat tabel Transaksi Pengembalian
$sqlTransaksiPengembalian = "CREATE TABLE IF NOT EXISTS `Transaksi_Pengembalian` (
    `ID_Pengembalian` INT AUTO_INCREMENT PRIMARY KEY,
    `Tanggal_Pengembalian` DATE NOT NULL,
    `Denda` DECIMAL(10,2),
    `ID_Peminjaman` INT,
    FOREIGN KEY (`ID_Peminjaman`) REFERENCES `Transaksi_Peminjaman`(`ID_Peminjaman`)
)";

if ($conn->query($sqlTransaksiPengembalian) === TRUE) {
    echo "Tabel Transaksi Pengembalian berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Menutup koneksi database
$conn->close();

