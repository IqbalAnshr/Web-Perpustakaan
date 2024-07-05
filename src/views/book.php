<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Section</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="./src/assets/css/book.css">
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



    <div class="container-xxl" id="main-container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <!-- Kategori -->
                <aside class="aside-container p-4 bg-light rounded-3 shadow-sm">
                    <h2 class="mb-4 fs-4">Filter Kategori</h2>
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a href="?filter=Fiksi" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-book me-2"></i> Fiksi
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?filter=Non-Fiksi"
                                class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-book-open me-2"></i> Non-Fiksi
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?filter=Anak-anak"
                                class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-child me-2"></i> Anak-anak
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?filter=Majalah" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-newspaper me-2"></i> Majalah
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?filter=Majalah Dewasa"
                                class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-newspaper me-2"></i> Majalah Dewasa
                            </a>
                        </li>
                    </ul>
                </aside>

                <!-- Penulis -->
                <aside class="aside-container p-4 bg-light rounded-3 shadow-sm mt-4">
                    <h2 class="mb-4 fs-4">Trending Penulis</h2>
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a href="?search=Jane Austen"
                                class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-pen me-2"></i> Jane Austen
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?search=search 2" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-pen me-2"></i> search 2
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?search=search 3" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-pen me-2"></i> search 3
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?search=search 4" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-pen me-2"></i> search 4
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="?search=search 5" class="d-flex align-items-center text-dark text-decoration-none">
                                <i class="fas fa-pen me-2"></i> Penulis 5
                            </a>
                        </li>
                    </ul>
                </aside>

            </div>



            <div class="col-md-9">
                <section class="book-container">
                    <div class="button-filter-container d-flex gap-2">
                        <form action="" method="GET">
                            <input type="hidden" name="">
                            <button
                                class="custom-filter-button <?php echo (!isset($_GET['filter']) ? 'custom-filter-active' : ''); ?>"
                                type="submit">Semua</button>
                        </form>
                        <form action="" method="GET">
                            <input type="hidden" name="filter" value="Popular">
                            <button
                                class="custom-filter-button <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Popular' ? 'custom-filter-active' : ''); ?>"
                                type="submit">Popular</button>
                        </form>
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

                    <div class="content-book d-flex justify-content-around flex-wrap mb-2">
                        <?php foreach ($books as $book): ?>
                            <div class="books-card" data-wow-delay="0.6s"
                                style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;">
                                <div class="dz-media">
                                    <img src="<?= $book['Sampul_Path'] ? htmlspecialchars('/public/' . $book['Sampul_Path']) : '/src/assets/images/default.png'; ?>"
                                        alt="book">
                                </div>
                                <div class="dz-content">
                                    <p class="title"><?= htmlspecialchars(truncateString($book['Judul'])) ?></p>
                                    <p class="author"><?= htmlspecialchars(truncateString($book['Penulis'], 15)) ?></p>
                                </div>
                                <a href="book/detail?isbn=<?= $book['ISBN'] ?>" class="hover-button">Detail</a>
                            </div>
                        <?php endforeach; ?>
                    </div>


                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php if ($previousPage > 0): ?>
                                <li class="page-item"><a class="page-link"
                                        href="?page=<?= $previousPage ?>&<?= $queryString ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link"
                                        href="?page=<?php echo $i; ?>&<?= $queryString ?>">
                                        <?php echo $i; ?>
                                    </a></li>
                            <?php endfor; ?>
                            <?php if ($nextPage <= $totalPages): ?>
                                <li class="page-item"><a class="page-link"
                                        href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </section>
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




    <script src="./src/assets/js/book.js"></script>
    <script>
        let inactivityTime = function () {
            let time;
            const redirectTime = 5 * 60 * 1000;;

            function redirectToHome() {
                window.location.href = '/';
            }

            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(redirectToHome, redirectTime);
            }

            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeydown = resetTimer;
            document.ontouchstart = resetTimer;
            document.onclick = resetTimer;
        };


        inactivityTime();

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>