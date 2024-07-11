<?php
$title = 'Transaksi Pengembalian';
include __DIR__ . '/../fragments/header.php';
include __DIR__ . '/../fragments/navbar.php';
include __DIR__ . '/../fragments/sidebar.php';

?>

<div class="content-wrapper" style="min-height: 1589.56px">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transaksi Pengembalian</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi Pengembalian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <form method="GET" action="" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari pengembalian..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="filter" class="form-control" value="<?php echo htmlspecialchars($filter); ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-control">
                            <option value="Tanggal_Pengembalian" <?php echo $sort == 'Tanggal_Pengembalian' ? 'selected' : ''; ?>>Tanggal Pengembalian</option>
                            <option value="Nama" <?php echo $sort == 'Nama' ? 'selected' : ''; ?>>Nama Anggota</option>
                            <option value="Judul" <?php echo $sort == 'Judul' ? 'selected' : ''; ?>>Judul Buku</option>
                            <option value="NIM_Anggota" <?php echo $sort == 'NIM_Anggota' ? 'selected' : ''; ?>>NIM
                                Anggota</option>
                            <option value="ISBN_Buku" <?php echo $sort == 'ISBN_Buku' ? 'selected' : ''; ?>>ISBN Buku
                            </option>
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

        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">

            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddReturn">
                Tambah Pengembalian
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
                        <th>ID Pengembalian</th>
                        <th>ID Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Denda</th>
                        <th>Nama Anggota</th>
                        <th>NIM Anggota</th>
                        <th>Judul Buku</th>
                        <th>ISBN Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($returns)) : ?>
                        <?php foreach ($returns as $return) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($return['ID_Pengembalian']); ?></td>
                                <td><?php echo htmlspecialchars($return['ID_Peminjaman']); ?></td>
                                <td><?php echo htmlspecialchars($return['Tanggal_Pengembalian']); ?></td>
                                <td><?php echo htmlspecialchars($return['Denda']); ?></td>
                                <td><?php echo htmlspecialchars($return['Nama']); ?></td>
                                <td><?php echo htmlspecialchars($return['NIM_Anggota']); ?></td>
                                <td><?php echo htmlspecialchars($return['Judul']); ?></td>
                                <td><?php echo htmlspecialchars($return['ISBN_Buku']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modalEditReturn<?php echo $return['ID_Pengembalian']; ?>">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEditReturn<?php echo $return['ID_Pengembalian']; ?>" tabindex="-1" aria-labelledby="modalEditReturnLabel<?php echo $return['ID_Pengembalian']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="/admin/returns/update">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditReturnLabel<?php echo $return['ID_Pengembalian']; ?>">
                                                            Edit Pengembalian
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_pengembalian" value="<?php echo htmlspecialchars($return['ID_Pengembalian']); ?>">
                                                        <div class="mb-3">
                                                            <label for="id_peminjaman_edit" class="form-label">ID
                                                                Peminjaman</label>
                                                            <input type="text" class="form-control" id="id_peminjaman_edit" name="id_peminjaman" value="<?php echo htmlspecialchars($return['ID_Peminjaman']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_pengembalian_edit" class="form-label">Tanggal
                                                                Pengembalian</label>
                                                            <input type="date" class="form-control" id="tanggal_pengembalian_edit" name="tanggal_pengembalian" value="<?php echo htmlspecialchars($return['Tanggal_Pengembalian']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="denda_edit" class="form-label">Denda</label>
                                                            <input type="number" class="form-control" id="denda_edit" name="denda" value="<?php echo htmlspecialchars($return['Denda']); ?>" required>
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

                                    <!-- Form Delete -->
                                    <form method="POST" action="/admin/returns/delete" class="ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_pengembalian" value="<?php echo htmlspecialchars($return['ID_Pengembalian']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data pengembalian.</td>
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
        </div><!-- /.container-fluid -->
    </div>
</div>

<!-- Modal Tambah Pengembalian -->
<div class="modal fade" id="modalAddReturn" tabindex="-1" aria-labelledby="modalAddReturnLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/returns/store">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddReturnLabel">Tambah Pengembalian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_peminjaman" class="form-label">ID Peminjaman</label>
                        <select class="form-control" id="id_peminjaman" name="id_peminjaman" required>
                            <option value="">Pilih ID Peminjaman</option>
                            <?php foreach ($unreturnedTransactions as $transaction) : ?>
                                <option value="<?php echo $transaction['ID_Peminjaman']; ?>">
                                    <?php echo htmlspecialchars($transaction['ID_Peminjaman']) . ' - ' . htmlspecialchars($transaction['Judul']) . ' - ' . htmlspecialchars($transaction['NIM']) . ' - ' . htmlspecialchars($transaction['Nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required>
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
<!-- End Modal Tambah Pengembalian -->

<?php
include __DIR__ . '/../fragments/footer.php';
?>