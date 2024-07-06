<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Peminjaman</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="/../src/assets/css/form_borrowing.css">
</head>

<body>
    <?php
    session_start();
    if (isset($_GET['clear_verification']) && $_GET['clear_verification'] === 'true') {
        unset($_SESSION['kode_verifikasi']);
        unset($_SESSION['kode_verifikasi_timestamp']);
        unset($_SESSION['nim']);
        unset($_SESSION['isbn']);
        $_SESSION['message'] = 'Silahkan Masukkan Kembali NIM Anda.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } ?>
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Verifikasi Peminjaman</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Formulir Memasukkan NIM -->
            <?php if (!isset($_SESSION['kode_verifikasi'])): ?>
                <form id="nim-form" method="POST" action="/book/borrowings/request-verification">
                    <div class="alert alert-info mt-3 mb-3" role="alert">
                        <i class="fa fa-envelope me-2"></i>
                        Kode verifikasi akan dikirimkan ke email yang terdaftar dengan NIM Anda. Pastikan email Anda aktif
                        dan
                        cek folder spam jika tidak menerima email.
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" name="isbn" id="isbn" value="<?= htmlspecialchars($_GET['isbn']) ?>">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" name="nim" id="nim" placeholder="Masukkan NIM"
                            value="<?= htmlspecialchars($_SESSION['nim'] ?? '') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Kirim Kode Verifikasi</button>
                    <a href="/book/detail?isbn=<?= htmlspecialchars($_GET['isbn']) ?>"
                        class="btn btn-secondary w-100">Kembali</a>
                </form>
            <?php endif; ?>

            <!-- Formulir Memasukkan Kode Verifikasi -->
            <?php if (isset($_SESSION['kode_verifikasi'])): ?>
                <form id="verification-form" method="POST" action="/book/borrowings/processBorrowing">
                    <div class="form-group mb-3">
                        <input type="hidden" name="nim" value="<?= htmlspecialchars($_SESSION['nim']) ?>">
                        <input type="hidden" name="isbn" value="<?= htmlspecialchars($_SESSION['isbn']) ?>">
                        <label for="verification-code">Kode Verifikasi</label>
                        <input type="text" class="form-control" name="kode_verifikasi" id="verification-code"
                            placeholder="Masukkan Kode Verifikasi" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Verifikasi</button>
                    <a href="/book/borrowings/create?isbn=<?= htmlspecialchars($_SESSION['isbn']) ?>&clear_verification=true"
                        class="btn btn-secondary w-100">Kembali</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>