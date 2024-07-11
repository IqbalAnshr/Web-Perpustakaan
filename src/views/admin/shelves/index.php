<?php
$title = 'Rak';
include __DIR__ . '/../fragments/header.php';
include __DIR__ . '/../fragments/navbar.php';
include __DIR__ . '/../fragments/sidebar.php';
?>

<div class="content-wrapper" style="min-height: 1589.56px">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Shelves</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Shelves</li>
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
                        <input type="text" name="search" class="form-control" placeholder="Cari rak..."
                            value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="filter" class="form-control">
                            <option value="" <?php echo $filter == '' ? 'selected' : ''; ?>>Semua Kategori</option>
                            <?php foreach ($shelves as $shelve): ?>
                                <option value="<?php echo $shelve['Kategori']; ?>" <?php echo $filter == $shelve['Kategori'] ? 'selected' : ''; ?>>
                                    <?php echo $shelve['Kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-control">
                            <option value="ID_Rak" <?php echo $sort == 'ID_Rak' ? 'selected' : ''; ?>>ID Rak</option>
                            <option value="Lokasi" <?php echo $sort == 'Lokasi' ? 'selected' : ''; ?>>Lokasi</option>
                            <option value="Kapasitas" <?php echo $sort == 'Kapasitas' ? 'selected' : ''; ?>>Kapasitas
                            </option>
                            <option value="Kategori" <?php echo $sort == 'Kategori' ? 'selected' : ''; ?>>Kategori
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
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddRack">
                Tambah Rak
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
                        <th>ID Rak</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($shelves)): ?>
                        <?php foreach ($shelves as $shelve): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($shelve['ID_Rak']); ?></td>
                                <td><?php echo htmlspecialchars($shelve['Lokasi']); ?></td>
                                <td><?php echo htmlspecialchars($shelve['Kapasitas']); ?></td>
                                <td><?php echo htmlspecialchars($shelve['Kategori']); ?></td>
                                <td><?php echo htmlspecialchars($shelve['Keterangan']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalUpdateRack<?php echo $shelve['ID_Rak']; ?>">Update</button>
                                    <form class="m-1" method="POST" action="/admin/shelves/delete"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus rak ini?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_rak" value="<?php echo $shelve['ID_Rak']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>

                                <!-- modal form update rak -->
                                <div class="modal fade" id="modalUpdateRack<?php echo $shelve['ID_Rak']; ?>" tabindex="-1"
                                    aria-labelledby="modalUpdateRackLabel<?php echo $shelve['ID_Rak']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="/admin/shelves/update" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="modalUpdateRackLabel<?php echo $shelve['ID_Rak']; ?>">Edit Rak</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_rak" value="<?php echo $shelve['ID_Rak']; ?>">
                                                    <div class="mb-3">
                                                        <label for="lokasi" class="form-label">Lokasi</label>
                                                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                                                            required value="<?php echo $shelve['Lokasi']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kapasitas" class="form-label">Kapasitas</label>
                                                        <input type="number" class="form-control" id="kapasitas"
                                                            name="kapasitas" required
                                                            value="<?php echo $shelve['Kapasitas']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kategori" class="form-label">Kategori</label>
                                                        <input type="text" class="form-control" id="kategori" name="kategori"
                                                            required value="<?php echo $shelve['Kategori']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                        <textarea class="form-control" id="keterangan" name="keterangan"
                                                            required><?php echo $shelve['Keterangan']; ?></textarea>
                                                    </div>
                                                    <input type="hidden" name="id_rak_lama"
                                                        value="<?php echo $shelve['ID_Rak']; ?>">
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
                                <!-- end of modal form update rak -->
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data rak</td>
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
                        <li class="page-item"><a class="page-link"
                                href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

</div>

<!-- modal form tambah rak -->
<div class="modal fade" id="modalAddRack" tabindex="-1" aria-labelledby="modalAddRackLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/shelves/store" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddRackLabel">Tambah Rak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="kapasitas" class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Add rak</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of modal form tambah rak -->


<?php
include __DIR__ . '/../fragments/control_sidebar.php';
include __DIR__ . '/../fragments/footer.php';
?>