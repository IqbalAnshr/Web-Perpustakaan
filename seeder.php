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
('0987654321', 'Jane Doe', 'Jl. Thamrin No. 2', '081098765432', 'janedoe@example.com'),
('1112131415', 'Michael Johnson', 'Jl. Merdeka No. 3', '081112233445', 'michaelj@example.com'),
('1617181920', 'Emily Davis', 'Jl. Pahlawan No. 4', '081223344556', 'emilyd@example.com'),
('2122232425', 'David Smith', 'Jl. Kenangan No. 5', '081334455667', 'davids@example.com'),
('2627282930', 'Sophia Wilson', 'Jl. Harapan No. 6', '081445566778', 'sophiaw@example.com'),
('3132333435', 'James Brown', 'Jl. Impian No. 7', '081556677889', 'jamesb@example.com'),
('3637383940', 'Olivia Taylor', 'Jl. Cinta No. 8', '081667788990', 'oliviat@example.com'),
('4142434445', 'William Jones', 'Jl. Persahabatan No. 9', '081778899001', 'williamj@example.com'),
('4647484950', 'Isabella Garcia', 'Jl. Kemenangan No. 10', '081889900112', 'isabellag@example.com'),
('5152535455', 'Benjamin Martinez', 'Jl. Kedamaian No. 11', '081990011223', 'benjaminm@example.com'),
('5657585960', 'Ava Hernandez', 'Jl. Keadilan No. 12', '082001122334', 'avah@example.com')";

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
$sqlBukuSeed = "INSERT INTO Buku (ISBN, Judul, Penulis, Penerbit, Tahun_Terbit, Sinopsis, Jumlah_Total, Jumlah_Tersedia, Status_Pinjam, Sampul_Path, ID_Rak)
VALUES
  ('1234567890123', 'The Lord of the Rings', 'J.R.R. Tolkien', 'Allen & Unwin', 1954, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.', 5, 5, FALSE, NULL, 1),
  ('9876543210987', 'The Hobbit', 'J.R.R. Tolkien', 'Allen & Unwin', 1937, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 3, 3, FALSE, NULL, 1),
  ('0123456789012', 'Pride and Prejudice', 'Jane Austen', 'Penguin Books', 1813, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.', 8, 6, TRUE, NULL, 2),
  ('7894561230456', 'To Kill a Mockingbird', 'Harper Lee', 'HarperCollins', 1960, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.', 10, 8, FALSE, NULL, 3),
  ('3456789012345', 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', 1951, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque.', 7, 5, TRUE, NULL, 3),
  ('6789012345678', '1984', 'George Orwell', 'Secker & Warburg', 1949, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti.', 6, 4, FALSE, NULL, 4),
  ('9012345678901', 'Animal Farm', 'George Orwell', 'Secker & Warburg', 1945, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus.', 9, 7, TRUE, NULL, 4),
  ('2345678901234', 'Brave New World', 'Aldous Huxley', 'Chatto & Windus', 1932, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.', 12, 10, FALSE, NULL, 4),
  ('5678901234567', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Charles Scribners Sons', 1925, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae.', 8, 6, TRUE, NULL, 5),
  ('8901234567890', 'One Hundred Years of Solitude', 'Gabriel García Márquez', 'Editorial Sudamericana', 1967, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Itaque earum rerum hic tenetur a sapiente delectus.', 15, 12, FALSE, NULL, 5),
  ('1234567890122', 'The Grapes of Wrath', 'John Steinbeck', 'Viking Press', 1939, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 11, 9, TRUE, NULL, 6),
  ('4567890123456', 'Invisible Man', 'Ralph Ellison', 'Random House', 1952, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.', 9, 7, FALSE, NULL, 6),
  ('7890123456789', 'Beloved', 'Toni Morrison', 'Alfred A. Knopf', 1987, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.', 14, 11, TRUE, NULL, 6)";


if ($conn->query($sqlBukuSeed) === TRUE) {
    echo "Data Buku berhasil di-seed.<br>";
} else {
    echo "Error seeding Buku: " . $conn->error . "<br>";
}

// Seeder untuk tabel Transaksi Peminjaman
$sqlTransaksiPeminjamanSeed = "INSERT INTO `Transaksi_Peminjaman` (`Tanggal_Peminjaman`, `Tanggal_Jatuh_Tempo`, `NIM_Anggota`, `ISBN_Buku`, `Status_Pengembalian`) VALUES 
('2024-06-01', '2024-06-10', '1234567890', '5678901234567', 1),
('2024-06-05', '2024-06-15', '0987654321', '7890123456789', 1),
('2024-07-01', '2024-07-10', '1112131415', '1234567890123', 1),
('2024-07-02', '2024-07-12', '1617181920', '9876543210987', 1),
('2024-07-03', '2024-07-13', '2122232425', '0123456789012', 1),
('2024-07-04', '2024-07-14', '2627282930', '7894561230456', 1),
('2024-07-05', '2024-07-15', '3132333435', '3456789012345', 1),
('2024-07-06', '2024-07-16', '3637383940', '6789012345678', 1),
('2024-07-07', '2024-07-17', '4142434445', '9012345678901', 1),
('2024-07-08', '2024-07-18', '4647484950', '2345678901234', 1),
('2024-07-09', '2024-07-19', '5152535455', '5678901234567', 1),
('2024-07-10', '2024-07-20', '5657585960', '8901234567890', 1),
('2024-03-01', '2024-03-10', '1234567890', '5678901234567', 1),
('2024-03-05', '2024-03-15', '0987654321', '7890123456789', 1),
('2024-04-01', '2024-04-10', '1112131415', '1234567890123', 1),
('2024-04-02', '2024-04-12', '1617181920', '9876543210987', 1),
('2024-04-03', '2024-04-13', '2122232425', '0123456789012', 1),
('2024-04-04', '2024-04-14', '2627282930', '7894561230456', 1),
('2024-05-01', '2024-05-10', '3132333435', '3456789012345', 1),
('2024-05-02', '2024-05-12', '3637383940', '6789012345678', 1),
('2024-05-03', '2024-05-13', '4142434445', '9012345678901', 1),
('2024-05-04', '2024-05-14', '4647484950', '2345678901234', 1)";

if ($conn->query($sqlTransaksiPeminjamanSeed) === TRUE) {
    echo "Data Transaksi Peminjaman berhasil di-seed.<br>";
} else {
    echo "Error seeding Transaksi Peminjaman: " . $conn->error . "<br>";
}


// Seeder untuk tabel Transaksi Pengembalian
$sqlTransaksiPengembalianSeed = "INSERT INTO `Transaksi_Pengembalian` (`Tanggal_Pengembalian`, `Denda`, `ID_Peminjaman`) VALUES 
('2024-06-10', 0.00, 1),
('2024-06-15', 0.00, 2),
('2024-07-10', 5000.00, 3),
('2024-07-12', 0.00, 4),
('2024-07-13', 10000.00, 5),
('2024-07-14', 0.00, 6),
('2024-07-15', 2000.00, 7),
('2024-07-16', 0.00, 8),
('2024-07-17', 15000.00, 9),
('2024-07-18', 0.00, 10),
('2024-07-19', 0.00, 11),
('2024-07-20', 3000.00, 12),
('2024-08-10', 0.00, 13),
('2024-08-15', 0.00, 14),
('2024-09-10', 5000.00, 15),
('2024-09-12', 0.00, 16),
('2024-09-13', 10000.00, 17),
('2024-09-14', 0.00, 18),
('2024-10-01', 2000.00, 19),
('2024-10-02', 0.00, 20),
('2024-10-03', 15000.00, 21),
('2024-10-04', 0.00, 22)";

if ($conn->query($sqlTransaksiPengembalianSeed) === TRUE) {
    echo "Data Transaksi Pengembalian berhasil di-seed.<br>";
} else {
    echo "Error seeding Transaksi Pengembalian: " . $conn->error . "<br>";
}

// Menutup koneksi database
$conn->close();
?>
