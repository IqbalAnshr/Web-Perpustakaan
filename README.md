
# Web App Perpustakaan Online

Aplikasi web ini dirancang untuk membantu mengelola perpustakaan kampus dan memungkinkan pengguna untuk meminjam buku.

Web ini dikembangkan sebagai bagian dari tugas mata kuliah Web Programming

## Fitur Utama
- **Pencarian Buku**: Pengguna dapat mencari buku berdasarkan judul, penulis, atau kategori.
- **Peminjaman Buku**: Admin dapat Mengelola dan Mengorganisir peminjaman dengan mengisi formulir peminjaman yang disediakan.
- **Pengembalian Buku dan Denda**: Admin dapat Mengelola Pengembalian. Otomatisasi denda sesuai keterlambatan pengembalian.
- **Statistik Peminjaman**: Admin dapat melihat statistik peminjaman buku dalam periode tertentu.
- **Peminjaman Mandiri oleh Mahasiswa**: Mahasiswa dapat meminjam buku tanpa perlu ke admin dengan memilih buku dan memasukkan NIM. Verifikasi akan dilakukan melalui email, dan informasi tentang peminjaman akan dikirim ke Gmail.
- **Dan Fitur Lainnya**

## Teknologi yang Digunakan
- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript

## Cara Install

### Clone Repository
```bash
git clone https://github.com/IqbalAnshr/Web-Perpustakaan.git
```

### Instal Dependencies

#### Backend
Pastikan Anda sudah menginstal Composer. Jalankan perintah berikut untuk menginstal dependensi PHP:
```bash
composer install
```

#### Frontend
Pastikan Anda sudah menginstal Node.js dan npm. Jalankan perintah berikut untuk menginstal dependensi frontend:
```bash
npm install
```

### Konfigurasi Environment
Buat file `.env` di direktori root project Anda dan tambahkan konfigurasi berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=""
```

Jika Anda menggunakan Mailhog untuk SMTP server, Anda tidak perlu menambahkan pengaturan email ke file `.env`.

Jika Anda ingin menggunakan Gmail SMTP server, aktifkan App Password dan tambahkan konfigurasi berikut ke file `.env`:
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_FROM_ADDRESS=tes@gmail.com
MAIL_FROM_NAME="Perpustakaan Ars University"
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=tes@gmail.com
MAIL_PASSWORD=your-app-password
```

### Jalankan Migrasi dan Seeder
Jalankan perintah berikut untuk melakukan migrasi database dan menambahkan data awal:
```bash
php migrate.php
php seeder.php
```

### Jalankan Aplikasi
Jalankan server PHP dengan perintah berikut:
```bash
php -S localhost:8000
```
Akses aplikasi di [http://localhost:8000](http://localhost:8000)

### Cara Menggunakan Mailhog
Jika Anda menggunakan Mailhog, Anda bisa mengakses Mailhog di [http://localhost:8025](http://localhost:8025) untuk melihat email yang dikirim selama pengujian. Anda tidak perlu mengatur variabel `MAIL_` di file `.env` jika menggunakan Mailhog.

## Kontak / Anggota
Jika Anda memiliki pertanyaan atau masukan, silakan hubungi kami melalui email:
- **Muhammad Iqbal Anshori**: iqbalanshr@gmail.com
- **Moch Rafly Pratama**: rafly@example.com
- **Muhammad Ali Zafar Sidiq**: zafar@example.com
- **Ilham Fahri Husaeni**: ilham@example.com
- **Aldi Supriyadi**: aldi@example.com

Semoga Web App Perpustakaan Online ini bermanfaat!
