<?php
$title = 'Books';
include __DIR__ . '/../fragments/header.php';
include __DIR__ . '/../fragments/navbar.php';
include __DIR__ . '/../fragments/sidebar.php';
?>

<div class="content-wrapper" style="min-height: 1589.56px">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Books</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Books</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <form method="GET" action="" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari buku..."
                            value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="filter" class="form-control">
                            <option value="" <?php echo $filter == '' ? 'selected' : ''; ?>>Semua Kategori</option>
                            <?php foreach ($raks as $rak): ?>
                                <option value="<?php echo $rak['Kategori']; ?>" <?php echo $filter == $rak['Kategori'] ? 'selected' : ''; ?>>
                                    <?php echo $rak['Kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-control">
                            <option value="Judul" <?php echo $sort == 'Judul' ? 'selected' : ''; ?>>Judul</option>
                            <option value="Penulis" <?php echo $sort == 'Penulis' ? 'selected' : ''; ?>>Penulis</option>
                            <option value="Penerbit" <?php echo $sort == 'Penerbit' ? 'selected' : ''; ?>>Penerbit
                            </option>
                            <!-- Tambahkan opsi sorting lain sesuai kebutuhan -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="order" class="form-control">
                            <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </form>

            <!-- tombol modal -->
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddBook">
                Tambah Buku
            </button>
            <?php if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == true): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == false): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Jumlah Total</th>
                        <th>Jumlah Tersedia</th>
                        <th>Status Pinjam</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $buku): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($buku['ISBN']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Judul']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Penulis']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Penerbit']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Tahun_Terbit']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Jumlah_Total']); ?></td>
                                <td><?php echo htmlspecialchars($buku['Jumlah_Tersedia']); ?></td>
                                <td><?php echo $buku['Status_Pinjam'] ? 'Dapat Dipinjam' : 'Tidak Dapat Dipinjam'; ?></td>
                                <td><?php echo htmlspecialchars($buku['Kategori']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalUpdateBook<?php echo $buku['ISBN']; ?>">Update</button>
                                    <form class="m-1" method="POST" action="/admin/books/delete"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="isbn" value="<?php echo $buku['ISBN']; ?>">
                                        <input type="hidden" name="sampul_path" value="<?php echo $buku['Sampul_Path']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-info btn-sm m-1" data-toggle="modal"
                                        data-target="#coverModal<?php echo $buku['ISBN']; ?>">Info</button>

                                </td>

                                <!-- modal form update book -->
                                <div class="modal fade" id="modalUpdateBook<?php echo $buku['ISBN']; ?>" tabindex="-1"
                                    aria-labelledby="modalUpdateBookLabel<?php echo $buku['ISBN']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="/admin/books/update" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="modalUpdateBookLabel<?php echo $buku['ISBN']; ?>">Edit Buku</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="isbn_baru" class="form-label">ISBN</label>
                                                        <input type="text" class="form-control" id="isbn_baru" name="isbn_baru"
                                                            required value="<?php echo $buku['ISBN']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="judul" class="form-label">Judul</label>
                                                        <input type="text" class="form-control" id="judul" name="judul" required
                                                            value="<?php echo $buku['Judul']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="penulis" class="form-label">Penulis</label>
                                                        <input type="text" class="form-control" id="penulis" name="penulis"
                                                            required value="<?php echo $buku['Penulis']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="penerbit" class="form-label">Penerbit</label>
                                                        <input type="text" class="form-control" id="penerbit" name="penerbit"
                                                            required value="<?php echo $buku['Penerbit']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                                        <input type="number" class="form-control" id="tahun_terbit"
                                                            name="tahun_terbit" required
                                                            value="<?php echo $buku['Tahun_Terbit']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah_total" class="form-label">Jumlah Total</label>
                                                        <input type="number" class="form-control" id="jumlah_total"
                                                            name="jumlah_total" required
                                                            value="<?php echo $buku['Jumlah_Total']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah_tersedia" class="form-label">Jumlah Tersedia</label>
                                                        <input type="number" class="form-control" id="jumlah_tersedia"
                                                            name="jumlah_tersedia" required
                                                            value="<?php echo $buku['Jumlah_Tersedia']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status_pinjam" class="form-label">Status Pinjam</label>
                                                        <select class="form-select" id="status_pinjam" name="status_pinjam"
                                                            required>
                                                            <option value="0" <?php echo ($buku['Status_Pinjam'] == 0) ? 'selected' : ''; ?>>Tidak Dapat Dipinjam</option>
                                                            <option value="1" <?php echo ($buku['Status_Pinjam'] == 1) ? 'selected' : ''; ?>>Dapat Dipinjam</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sampul_buku" class="form-label">Sampul</label>
                                                        <input type="file" class="form-control" id="sampul_buku" name="file">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kategori" class="form-label">ID Rak</label>
                                                        <select class="form-select" id="id_rak" name="id_rak" required>
                                                            <?php foreach ($raks as $rak): ?>
                                                                <option value="<?php echo $rak['ID_Rak']; ?>" <?php echo ($buku['ID_Rak'] == $rak['ID_Rak']) ? 'selected' : ''; ?>>
                                                                    <?php echo $rak['ID_Rak'] . ' - ' . $rak['Keterangan']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="sampul_path"
                                                        value="<?php echo $buku['Sampul_Path']; ?>">
                                                    <input type="hidden" name="isbn_lama" value="<?php echo $buku['ISBN']; ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" value="submit"
                                                        name="submit">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end of modal form update book -->

                                <!-- Modal for book cover -->
                                <div class="modal fade" id="coverModal<?php echo $buku['ISBN']; ?>" tabindex="-1"
                                    aria-labelledby="coverModalLabel<?php echo $buku['ISBN']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="coverModalLabel<?php echo $buku['ISBN']; ?>">Cover
                                                    Buku - <?php echo htmlspecialchars($buku['Judul']); ?></h5> <br>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-lg">Rak : <?php echo htmlspecialchars($buku['ID_Rak']); ?></p>
                                                <p class="text-lg">Keterangan :
                                                    <?php echo htmlspecialchars($buku['Keterangan']); ?>
                                                </p>
                                                <div class="img-container d-flex justify-content-center">
                                                    <img src="<?php echo $buku['Sampul_Path'] ? htmlspecialchars('/../public/' . $buku['Sampul_Path']) : '/../src/assets/images/default.png'; ?>"
                                                        alt="Cover Buku" class="img-fluid" style="max-width: 200px;">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data buku.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>



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
                        <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</div>


<!-- modal form create book -->
<div class="modal fade" id="modalAddBook" tabindex="-1" aria-labelledby="modalAddBookLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/books/store" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddBookLabel">Tambah Buku Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="penulis" name="penulis" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_total" class="form-label">Jumlah Total</label>
                        <input type="number" class="form-control" id="jumlah_total" name="jumlah_total" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_tersedia" class="form-label">Jumlah Tersedia</label>
                        <input type="number" class="form-control" id="jumlah_tersedia" name="jumlah_tersedia" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_pinjam" class="form-label">Status Pinjam</label>
                        <select class="form-select" id="status_pinjam" name="status_pinjam" required>
                            <option value="1">Dipinjam</option>
                            <option value="0">Tersedia</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sampul_buku" class="form-label">Sampul</label>
                        <input type="file" class="form-control" id="sampul_buku" name="file">
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">ID Rak</label>
                        <select class="form-select" id="id_rak" name="id_rak" required>
                            <?php foreach ($raks as $rak): ?>
                                <option value="<?php echo $rak['ID_Rak']; ?>">
                                    <?php echo $rak['ID_Rak'] . ' - ' . $rak['Keterangan']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>





<?php
include __DIR__ . '/../fragments/control_sidebar.php';
include __DIR__ . '/../fragments/footer.php';
?>