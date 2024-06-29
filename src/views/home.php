<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Buku</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./src/assets/css/home.css">
</head>

<body>
    <header id="" class="site-header">
        <nav id="navbar_top" class="navbar navbar-expand-lg ">
            <div class="container">
                <!-- <a class="navbar-brand" href="">
                    logo here
                </a>
                <a class="nav-phone" href="#">(555) 867-5309</a> -->
                <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Daftar</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown"> Sosmed </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a class="dropdown-item" href="#"> Instagram</a></li>
                                <li><a class="dropdown-item" href="#"> Twitter </a></li>
                                <li><a class="dropdown-item" href="#"> Facebook </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="container h-100">
            <div class="row align-items-end search-section">
                <div class="col-md-8 offset-md-2 text-center ">
                    <h1 class="headingSm white uc" data-aos="fade-right" data-aos-delay="400">Selamat Datang Di
                        Perpustakaan</h1>
                    <div class="input-group w-100 mt-4">
                        <input type="search" class="form-control bg-transparent border border-secondary"
                            placeholder="Search" aria-label="Search" aria-describedby="search-addon"
                            id="search-input" />
                        <button type="button" class="btn btn-outline-primary" onclick="redirectToSearch()"
                            data-mdb-ripple-init>Search</button>
                    </div>
                </div>
            </div>

            <?php
            function truncateString($string, $length = 10)
            {
                if (strlen($string) > $length) {
                    return substr($string, 0, $length) . '...';
                }
                return $string;
            }

            ?>

            <div class="container book-section mt-5">
                <h2 class="mb-4">Rekomendasi Buku</h2>
                <div class="swiper list-books">
                    <div class="swiper-wrapper">
                        <?php foreach ($books as $book): ?>
                            <div class="swiper-slide">
                                <div class="books-card" data-wow-delay="0.6s"
                                    style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;">
                                    <div class="dz-media">
                                        <img src="<?= htmlspecialchars($book['Sampul_Path'] ? '/public/' . $book['Sampul_Path'] : '/src/assets/images/default.png'); ?>"
                                            alt="book">
                                    </div>
                                    <button class="hover-button">Pinjam</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    </section>


    <section class="mobile-tool-bar bg-theme">
        <div class="row d-flex justify-content-evenly h-100">
            <div class="col text-center my-auto">
                <a class="white" href="#"><i class="fas fa-phone"></i></a>
            </div>

            <div class="col text-center my-auto">
                <a class="white" href="#" target="_blank"><i class="fab fa-instagram-square"></i></a>
            </div>

            <div class="col text-center my-auto">
                <a class="white" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </section>

    <script src="./src/assets/js/home.js"></script>
    <script>
        function redirectToSearch() {
            var searchQuery = document.getElementById('search-input').value;
            window.location.href = '/book?search=' + encodeURIComponent(searchQuery);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>


</html>