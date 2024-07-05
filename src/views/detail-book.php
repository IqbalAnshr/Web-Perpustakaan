<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Book</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../src/assets/css/detail_book.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fs-4" href="/">
                <strong>PerpusIni</strong>
            </a>
            <form class="d-flex ms-auto me-3 w-25" role="search" method="GET" action="/book">
                <div class="input-group">
                    <input class="form-control me-2 search" type="search"
                        placeholder="Search ISBN, Judul, Penulis, Penerbit" aria-label="Search" name="search"
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </nav>

    <body>
        <div class="container" id="main-container">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/book">Katalog Buku</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($book['Judul']) ?></li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-md-3">
                    <div class="book-cover">
                        <div class="image mb-4">
                            <img src="<?= htmlspecialchars('/../public/' . $book['Sampul_Path']) ?>" alt="book"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-book">
                        <h1><?= htmlspecialchars($book['Judul']) ?></h1>
                        <p><strong>Penulis:</strong> <?= htmlspecialchars($book['Penulis']) ?></p>
                        <p><strong>Penerbit:</strong> <?= htmlspecialchars($book['Penerbit']) ?></p>
                        <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($book['Tahun_Terbit']) ?></p>
                        <p><strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN']) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($book['Lokasi']) ?></p>
                        <p><strong>Kategori:</strong> <?= htmlspecialchars($book['Kategori']) ?></p>
                        <p><strong>Keterangan:</strong> <?= htmlspecialchars($book['Keterangan']) ?></p>

                        <h4>Sinopsis</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id arcu aliquet, elementum
                            nisi quis, condimentum nibh. Proin sed velit auctor, ullamcorper nisl nec, auctor odio.
                            Integer fermentum nisl vel eros facilisis posuere. Cras ac ligula mi. Nulla ac enim odio.
                            Sed varius viverra ornare. Nulla facilisi. Duis sed sem purus. Integer dictum nisi vitae
                            arcu varius ultricies. Curabitur eget ante malesuada, luctus nulla at, efficitur ex.</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="borrow-info">
                        <h4><i class="fas fa-book-open"></i>
                            <?= $book['Status_Pinjam'] == 1 ? 'Boleh Dipinjam' : 'Tidak Boleh Dipinjam' ?>
                        </h4>
                        <h4><i class="fas fa-users"></i>Jumlah Peminjam:
                            <?= htmlspecialchars($book['Jumlah_Total'] - $book['Jumlah_Tersedia']) ?>
                        </h4>
                        <h4><i class="fas fa-database"></i>Jumlah Total: <?= htmlspecialchars($book['Jumlah_Total']) ?>
                        </h4>
                        <h4><i class="fas fa-cubes"></i>Jumlah Tersedia:
                            <?= htmlspecialchars($book['Jumlah_Tersedia']) ?>
                        </h4>
                        <button class="btn btn-primary w-100 mb-2" <?= $book['Status_Pinjam'] != 1 ? 'disabled' : '' ?>>
                            <i class="fas fa-hand-paper"></i> Pinjam
                        </button>
                    </div>
                </div>
            </div>

            <div class="book-section">
                <h2 class="mb-4">Rekomendasi Buku</h2>
                <div class="swiper list-books">
                    <div class="swiper-wrapper">
                        <?php foreach ($books as $book): ?>
                            <div class="swiper-slide">
                                <div class="books-card">
                                    <div class="dz-media">
                                        <img src="<?= htmlspecialchars($book['Sampul_Path'] ? '/public/' . $book['Sampul_Path'] : '/src/assets/images/default.png'); ?>"
                                            alt="book">
                                    </div>
                                    <a href="/book/detail?isbn=<?= $book['ISBN'] ?>" class="hover-button">Detail</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Pagination and Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>


        </div>

        <footer class="bg-dark text-white text-center mt-5 pt-5 pb-3">
            <div class="container">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <h5 class="text-uppercase mb-4">Follow Us</h5>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-google"></i></a>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-github"></i></a>
                </section>
                <!-- Section: Social media -->

                <!-- Section: About -->
                <section class="mb-4">
                    <p class="text-muted">Your go-to place for all things books. Discover, Borrow, and Read.</p>
                </section>
                <!-- Section: About -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: #0056b3;">
                © 2024 Copyright:
                <a class="text-white" href="https://mdbootstrap.com/">MDBootstrap.com</a>
            </div>
            <!-- Copyright -->
        </footer>

        <script src="../src/assets/js/detail_book.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    </body>

</html>