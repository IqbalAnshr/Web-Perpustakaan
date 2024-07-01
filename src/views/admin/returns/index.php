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
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">

            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddReturn">
                Tambah Pengembalian
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
                        <th>ID Pengembalian</th>
                        <th>ID Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($returns)): ?>
                        <?php foreach ($returns as $return): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($return['ID_Pengembalian']); ?></td>
                                <td><?php echo htmlspecialchars($return['ID_Peminjaman']); ?></td>
                                <td><?php echo htmlspecialchars($return['Tanggal_Pengembalian']); ?></td>
                                <td><?php echo htmlspecialchars($return['Denda']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <!-- Tombol Kembalikan -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalEditReturn<?php echo $return['ID_Pengembalian']; ?>">Edit</button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEditReturn<?php echo $return['ID_Pengembalian']; ?>" tabindex="-1"
                                        aria-labelledby="modalEditReturnLabel<?php echo $return['ID_Pengembalian']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="/admin/returns/edit">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="modalEditReturnLabel<?php echo $return['ID_Pengembalian']; ?>">Edit Pengembalian</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Isi form edit pengembalian -->
                                                        <input type="hidden" name="id_pengembalian" value="<?php echo $return['ID_Pengembalian']; ?>">
                                                        <div class="mb-3">
                                                            <label for="id_peminjaman_edit" class="form-label">ID Peminjaman</label>
                                                            <input type="text" class="form-control" id="id_peminjaman_edit"
                                                                name="id_peminjaman" value="<?php echo $return['ID_Peminjaman']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_pengembalian_edit" class="form-label">Tanggal Pengembalian</label>
                                                            <input type="date" class="form-control" id="tanggal_pengembalian_edit"
                                                                name="tanggal_pengembalian" value="<?php echo $return['Tanggal_Pengembalian']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="denda_edit" class="form-label">Denda</label>
                                                            <input type="number" class="form-control" id="denda_edit"
                                                                name="denda" value="<?php echo $return['Denda']; ?>" required>
                                                        </div>
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
                                    <!-- End Modal Edit -->

                                    <!-- Tombol Hapus -->
                                    <form method="POST" action="/admin/returns/delete" class="ml-2"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <input type="hidden" name="id_pengembalian" value="<?php echo $return['ID_Pengembalian']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pengembalian.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div><!-- /.container-fluid -->
    </div>
</div>

<!-- Modal Tambah Pengembalian -->
<div class="modal fade" id="modalAddReturn" tabindex="-1" aria-labelledby="modalAddReturnLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/returns/add">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddReturnLabel">Tambah Pengembalian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_pengembalian" class="form-label">ID Pengembalian</label>
                        <input type="text" class="form-control" id="id_pengembalian" name="id_pengembalian" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_peminjaman" class="form-label">ID Peminjaman</label>
                        <input type="text" class="form-control" id="id_peminjaman" name="id_peminjaman" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required>
                    </div>
                    <div class="mb-3">
                        <label for="denda" class="form-label">Denda</label>
                        <input type="number" class="form-control" id="denda" name="denda" required>
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
