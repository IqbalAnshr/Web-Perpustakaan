    <?php
    $title = 'Borrowings';
    include __DIR__ . '/../fragments/header.php';
    include __DIR__ . '/../fragments/navbar.php';
    include __DIR__ . '/../fragments/sidebar.php';
    ?>

    <div class="content-wrapper" style="min-height: 1589.56px">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Borrowings</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Borrowings</li>
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
                            <input type="text" name="search" class="form-control" placeholder="Cari peminjaman..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="filter" class="form-control">
                                <option value="" <?php echo $filter == '' ? 'selected' : ''; ?>>ISBN Buku</option>
                                <?php foreach ($raks as $rak) : ?>
                                    <option value="<?php echo htmlspecialchars($rak['ISBN']); ?>" <?php echo $filter == $rak['ISBN'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($rak['ISBN']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-control">
                                <option value="Tanggal_Peminjaman" <?php echo $sort == 'Tanggal_Peminjaman' ? 'selected' : ''; ?>>Tanggal Peminjaman</option>
                                <option value="Tanggal_Pengembalian" <?php echo $sort == 'Tanggal_Pengembalian' ? 'selected' : ''; ?>>Tanggal Pengembalian</option>
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
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddBorrowing">
                    Tambah Peminjaman
                </button>
                <?php if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == true) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['message']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                    </div>
                <?php elseif (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == false) : ?>
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
                            <th>ID Peminjaman</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Tanggal Pengembalian</th>
                            <th>NIM Anggota</th>
                            <th>ISBN Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($borrowings)) : ?>
                            <?php foreach ($borrowings as $borrowing) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($borrowing['ID_Peminjaman']); ?></td>
                                    <td><?php echo htmlspecialchars($borrowing['Tanggal_Peminjaman']); ?></td>
                                    <td><?php echo htmlspecialchars($borrowing['Tanggal_Pengembalian']); ?></td>
                                    <td><?php echo htmlspecialchars($borrowing['NIM_Anggota']); ?></td>
                                    <td><?php echo htmlspecialchars($borrowing['ISBN_Buku']); ?></td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalUpdateBorrowing<?php echo $borrowing['ID_Peminjaman']; ?>">Update</button>
                                        <form class="m-1" method="POST" action="/admin/borrowings/delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="id_peminjaman" value="<?php echo $borrowing['ID_Peminjaman']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>

                                    <!-- modal form update borrowing -->
                                    <div class="modal fade" id="modalUpdateBorrowing<?php echo $borrowing['ID_Peminjaman']; ?>" tabindex="-1" aria-labelledby="modalUpdateBorrowingLabel<?php echo $borrowing['ID_Peminjaman']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="/admin/borrowings/update" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalUpdateBorrowingLabel<?php echo $borrowing['ID_Peminjaman']; ?>">Edit Peminjaman</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                                                            <input type="date" class="form-control" id="tanggal_peminjaman" name="tanggal_peminjaman" required value="<?php echo $borrowing['Tanggal_Peminjaman']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                                                            <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required value="<?php echo $borrowing['Tanggal_Pengembalian']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nim_anggota" class="form-label">NIM Anggota</label>
                                                            <select class="form-control" id="nim_anggota" name="nim_anggota" required>
                                                                <?php foreach ($members as $member) : ?>
                                                                    <option value="<?php echo htmlspecialchars($member['NIM']); ?>" <?php echo $borrowing['NIM_Anggota'] == $member['NIM'] ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($member['NIM']) . ' - ' . htmlspecialchars($member['Nama']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="isbn_buku" class="form-label">ISBN Buku</label>
                                                            <select class="form-control" id="isbn_buku" name="isbn_buku" required>
                                                                <?php foreach ($raks as $rak) : ?>
                                                                    <option value="<?php echo htmlspecialchars($rak['ISBN']); ?>" <?php echo $borrowing['ISBN_Buku'] == $rak['ISBN'] ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($rak['ISBN']) . ' - ' . htmlspecialchars($rak['Status_Pinjam']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="id_peminjaman" value="<?php echo $borrowing['ID_Peminjaman']; ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" value="submit" name="submit">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end of modal form update borrowing -->

                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($previousPage > 0) : ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $previousPage ?>&<?= $queryString ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&<?= $queryString ?>">
                                    <?php echo $i; ?>
                                </a></li>
                        <?php endfor; ?>
                        <?php if ($nextPage <= $totalPages) : ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- modal form add borrowing -->
    <div class="modal fade" id="modalAddBorrowing" tabindex="-1" aria-labelledby="modalAddBorrowingLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/admin/borrowings/store" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddBorrowingLabel">Tambah Peminjaman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" id="tanggal_peminjaman" name="tanggal_peminjaman" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required>
                        </div>
                        <div class="mb-3">
                            <label for="nim_anggota" class="form-label">NIM Anggota</label>
                            <select class="form-control" id="nim_anggota" name="nim_anggota" required>
                                <?php foreach ($members as $member) : ?>
                                    <option value="<?php echo htmlspecialchars($member['NIM']); ?>">
                                        <?php echo htmlspecialchars($member['NIM']) . ' - ' . htmlspecialchars($member['Nama']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="isbn_buku" class="form-label">ISBN Buku</label>
                            <select class="form-control" id="isbn_buku" name="isbn_buku" required>
                                <?php foreach ($raks as $rak) : ?>
                                    <option value="<?php echo htmlspecialchars($rak['ISBN']); ?>">
                                        <?php echo htmlspecialchars($rak['ISBN']) . ' - ' . htmlspecialchars($rak['Status_Pinjam']); ?>
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
    <!-- end of modal form add borrowing -->



    <?php
    include __DIR__ . '/../fragments/footer.php';
    ?>